<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\LLMService;
use App\Services\RAGService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MessageController extends Controller
{
    public function __construct(
        private LLMService $llmService,
        private RAGService $ragService
    ) {
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string',
            'is_image_request' => 'nullable|boolean',
            'image_size' => 'nullable|string|in:1:1,16:9,9:16',
        ]);

        $conversation = Conversation::findOrFail($request->input('conversation_id'));
        $this->authorize('view', $conversation);

        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $request->input('content'),
        ]);

        $context = $this->ragService->retrieve(
            $conversation->agent_id,
            $request->input('content')
        );

        // Check Quota before API Call
        $keyInfo = $this->llmService->getKeyInfo();
        $apiUsage = $keyInfo['usage'] ?? 0;
        $apiLimit = $keyInfo['limit'] ?? 0;
        $multiplier = $this->llmService->getProfitMultiplier();

        if (($apiUsage * $multiplier) >= $apiLimit && $apiLimit > 0) {
            return response()->json([
                'error' => 'Quota pemakaian AI Anda telah habis. Harap hubungi admin untuk melakukan top-up.',
            ], 403);
        }

        $llmResponse = $this->llmService->chat(
            $conversation->agent,
            $conversation->messages,
            $context
        );

        $response = $llmResponse['content'];
        $usage = $llmResponse['usage'] ?? [];

        $metadata = [];

        $isImageRequest = $request->input('is_image_request', false);

        if ($conversation->agent->hasCapability('image') && ($isImageRequest || $this->needsImage($request->input('content')))) {
            $imageSize = $request->input('image_size', '1:1');
            $imageUrl = $this->llmService->generateImage($request->input('content'), $imageSize);
            $metadata['image_url'] = $imageUrl;
        }

        if ($conversation->agent->hasCapability('pdf') && $this->needsPdf($request->input('content'))) {
            $pdfPath = $this->generatePdf($response);
            $metadata['pdf_path'] = $pdfPath;
        }

        $assistantMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $response,
            'metadata' => $metadata ?: null,
            'prompt_tokens' => $usage['prompt_tokens'] ?? null,
            'completion_tokens' => $usage['completion_tokens'] ?? null,
            'total_tokens' => $usage['total_tokens'] ?? null,
        ]);

        if (isset($metadata['pdf_path'])) {
            $metadata['message_id'] = $assistantMessage->id;
            $assistantMessage->update(['metadata' => $metadata]);
        }

        return response()->json([
            'user_message' => $userMessage,
            'assistant_message' => $assistantMessage,
        ]);
    }

    public function downloadPdf(Message $message)
    {
        $this->authorize('view', $message->conversation);

        if (!$message->hasPdf()) {
            abort(404);
        }

        $path = $message->metadata['pdf_path'];

        return Storage::download($path);
    }

    private function needsImage(string $content): bool
    {
        $content = strtolower($content);
        return str_contains($content, 'generate')
            || str_contains($content, 'image')
            || str_contains($content, 'gambar')
            || str_contains($content, 'lukis')
            || str_contains($content, 'buatkan foto')
            || str_contains($content, 'tampilkan visual');
    }

    private function needsPdf(string $content): bool
    {
        $content = strtolower($content);
        return str_contains($content, 'pdf')
            || str_contains($content, 'report')
            || str_contains($content, 'laporan')
            || str_contains($content, 'unduh doc');
    }

    private function generatePdf(string $content): string
    {
        $pdf = Pdf::loadView('pdf.report', compact('content'));
        $filename = 'reports/' . uniqid() . '.pdf';
        Storage::put($filename, $pdf->output());

        return $filename;
    }
}
