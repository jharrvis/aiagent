<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\UploadedFile;
use App\Services\LLMService;
use App\Services\RAGService;
use App\Services\ExcelGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MessageController extends Controller
{
    public function __construct(
        private LLMService $llmService,
        private RAGService $ragService,
        private ExcelGenerator $excelGenerator
    ) {
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:51200', // Max 50MB
            'is_image_request' => 'nullable|boolean',
            'image_size' => 'nullable|string|in:1:1,16:9,9:16',
        ]);

        $conversation = Conversation::findOrFail($request->input('conversation_id'));
        $this->authorize('view', $conversation);

        $agent = $conversation->agent;
        $user = $request->user();
        $uploadedFile = null;
        $fileAnalysisResult = null;

        // Check if agent supports file analysis
        $canAnalyzeFiles = $agent->can_analyze_files ?? false;


        // Handle file upload if present
        if ($request->hasFile('file')) {
            if (!$canAnalyzeFiles) {
                return response()->json([
                    'error' => 'Agen ini tidak mendukung analisis file. Silakan hubungi administrator.',
                ], 403);
            }

            $file = $request->file('file');

            // Validate file type
            $allowedMimeTypes = [
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                'application/vnd.ms-excel', // .xls
                'text/csv',
                'text/plain',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ];

            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return response()->json([
                    'error' => 'Tipe file tidak didukung. File yang didukung: PDF, Word (.docx), Excel (.xlsx), CSV, TXT, dan Gambar (JPG, PNG, GIF, WebP).',
                ], 422);
            }

            // Store the file
            $fileExtension = $file->getClientOriginalExtension();
            $storedFilename = uniqid('file_') . '.' . $fileExtension;
            $filePath = $file->storeAs('uploaded-files', $storedFilename, 'public');

            // Determine file type
            $fileType = $this->determineFileType($file->getMimeType(), $fileExtension);

            // --- Check quota before heavy work ---
            if (!$user->is_admin && $user->token_balance <= 0) {
                return response()->json([
                    'error' => 'Kuota token Anda telah habis. Silakan lakukan Top-Up untuk terus mengobrol.',
                ], 403);
            }

            // Build message content for user message
            $messageContent = $request->input('content', '');
            $userQuery = $messageContent ?: 'Analisis file ini secara menyeluruh.';
            $fileInfo = "📎 File diupload: {$file->getClientOriginalName()}";
            if (!empty($messageContent)) {
                $displayContent = "{$fileInfo}\n\n{$messageContent}";
            } else {
                $displayContent = $fileInfo . "\n\nSilakan analisis file ini.";
            }

            // Create user message FIRST so we have a valid message_id
            $userMessage = Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'user',
                'content' => $displayContent,
            ]);

            // Now create the uploaded file record with a valid message_id
            $uploadedFile = UploadedFile::create([
                'conversation_id' => $conversation->id,
                'message_id' => $userMessage->id,
                'original_filename' => $file->getClientOriginalName(),
                'stored_filename' => $storedFilename,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_type' => $fileType,
            ]);

            // Analyze the file using LLM
            $fileAnalysisResult = $this->llmService->analyzeFile(
                $agent,
                $filePath,
                $file->getClientOriginalName(),
                $userQuery
            );

            // Check for analysis errors
            if (isset($fileAnalysisResult['error'])) {
                Log::error('File analysis failed', ['error' => $fileAnalysisResult['error']]);
            }

            $response = $fileAnalysisResult['content'];
            $usage = $fileAnalysisResult['usage'] ?? [];

            $assistantMessage = Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $response,
                'metadata' => [
                    'file_analysis' => true,
                    'file_info' => $fileAnalysisResult['file_info'] ?? null,
                ],
                'prompt_tokens' => $usage['prompt_tokens'] ?? null,
                'completion_tokens' => $usage['completion_tokens'] ?? null,
                'total_tokens' => $usage['total_tokens'] ?? null,
            ]);

            // Update analysis summary in uploaded file
            $uploadedFile->update([
                'analysis_summary' => \Illuminate\Support\Str::limit($response, 500),
            ]);

            // Deduct Token Balance (Skip if Admin)
            if (!$user->is_admin && isset($usage['total_tokens'])) {
                $user->decrement('token_balance', $usage['total_tokens']);
                if ($user->token_balance < 0) {
                    $user->update(['token_balance' => 0]);
                }
            }

            return response()->json([
                'user_message' => $userMessage,
                'assistant_message' => $assistantMessage,
                'uploaded_file' => $uploadedFile,
                'token_balance' => $user->token_balance,
            ]);
        }

        // Regular chat flow (no file upload)
        // Check User Quota (Skip if Admin)
        if (!$user->is_admin && $user->token_balance <= 0) {
            return response()->json([
                'error' => 'Kuota token Anda telah habis. Silakan lakukan Top-Up untuk terus mengobrol.',
            ], 403);
        }

        // Validate content
        $messageContent = $request->input('content', '');
        if (empty($messageContent)) {
            return response()->json([
                'error' => 'Pesan atau file harus diisi.',
            ], 422);
        }

        // Create user message
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $messageContent,
        ]);

        // RAG retrieval (with error handling)
        $context = null;
        try {
            $context = $this->ragService->retrieve(
                $conversation->agent_id,
                $request->input('content')
            );
        } catch (\Exception $e) {
            Log::warning('RAG retrieval failed, continuing without context', [
                'agent_id' => $conversation->agent_id,
                'error' => $e->getMessage(),
            ]);
        }

        $llmResponse = $this->llmService->chat(
            $conversation->agent,
            $conversation->messages,
            $context
        );

        $response = $llmResponse['content'];
        $usage = $llmResponse['usage'] ?? [];
        $citations = $llmResponse['citations'] ?? [];
        $reasoning = $llmResponse['reasoning'] ?? '';

        $metadata = [];

        if (!empty($reasoning)) {
            $metadata['reasoning'] = $reasoning;
        }

        if (!empty($citations)) {
            $metadata['citations'] = $citations;
        }

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

        if ($conversation->agent->hasCapability('excel') && $this->needsExcel($request->input('content'))) {
            try {
                $excelData = $this->extractExcelData($request->input('content'), $response);
                $excelRelativePath = $this->excelGenerator->createProfitFirstWorkbook($excelData);
                $metadata['excel_path'] = $excelRelativePath;
                $metadata['excel_url'] = Storage::disk('public')->url($excelRelativePath);
                $metadata['excel_filename'] = 'Profit_First_Calculator.xlsx';
                $response .= "\n\n📊 **File Excel berhasil dibuat!** Silakan unduh menggunakan tombol di bawah.";
            } catch (\Exception $e) {
                Log::error('Excel generation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
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

        // Deduct Token Balance (Skip if Admin)
        if (!$user->is_admin && isset($usage['total_tokens'])) {
            $user->decrement('token_balance', $usage['total_tokens']);
            if ($user->token_balance < 0) {
                $user->update(['token_balance' => 0]);
            }
        }

        if (isset($metadata['pdf_path'])) {
            $metadata['message_id'] = $assistantMessage->id;
            $assistantMessage->update(['metadata' => $metadata]);
        }

        if (isset($metadata['excel_path'])) {
            $metadata['message_id'] = $assistantMessage->id;
            $assistantMessage->update(['metadata' => $metadata]);
        }

        return response()->json([
            'user_message' => $userMessage,
            'assistant_message' => $assistantMessage,
            'token_balance' => $user->token_balance,
        ]);
    }

    private function determineFileType(string $mimeType, string $extension): string
    {
        return match (true) {
            str_contains($mimeType, 'pdf') => 'pdf',
            str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet') || in_array($extension, ['xlsx', 'xls', 'csv']) => 'excel',
            str_contains($mimeType, 'word') || in_array($extension, ['docx', 'doc']) => 'word',
            str_contains($mimeType, 'text') || in_array($extension, ['txt', 'md', 'json', 'xml']) => 'text',
            str_contains($mimeType, 'image') => 'image',
            default => 'other',
        };
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

    public function downloadExcel(Message $message)
    {
        $this->authorize('view', $message->conversation);

        if (!isset($message->metadata['excel_path'])) {
            abort(404);
        }

        $path = $message->metadata['excel_path'];

        $filename = $message->metadata['excel_filename'] ?? 'Excel_Download.xlsx';

        // Use 'public' disk since Excel files are stored in storage/app/public
        return Storage::disk('public')->download($path, $filename);
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

    private function needsExcel(string $content): bool
    {
        $content = strtolower($content);
        return str_contains($content, 'excel')
            || str_contains($content, 'spreadsheet')
            || str_contains($content, 'profit first')
            || str_contains($content, 'hitung profit')
            || str_contains($content, 'buatkan file')
            || str_contains($content, 'download excel')
            || str_contains($content, 'unduh excel');
    }

    private function extractExcelData(string $userInput, string $aiResponse): array
    {
        // Extract numbers from conversation for Profit First calculation
        // Default values if not specified
        $data = [
            'omzet' => 0,
            'profit_percent' => '5%',
            'owner_pay_percent' => '50%',
            'tax_percent' => '15%',
            'opex_percent' => '30%',
        ];

        // Try to extract omzet value from input
        if (preg_match('/(?:omzet|pendapatan|penjualan)[^\d]*(\d+(?:[.,]\d+)?)/i', $userInput, $matches)) {
            $data['omzet'] = floatval(str_replace(',', '.', $matches[1]));
        }

        // Try to extract from AI response if contains numbers
        if (preg_match('/(?:omzet|pendapatan)[^\d]*(\d+(?:[.,]\d+)?)/i', $aiResponse, $matches)) {
            $data['omzet'] = floatval(str_replace(',', '.', $matches[1]));
        }

        return $data;
    }

    private function generatePdf(string $content): string
    {
        $pdf = Pdf::loadView('pdf.report', compact('content'));
        $filename = 'reports/' . uniqid() . '.pdf';
        Storage::put($filename, $pdf->output());

        return $filename;
    }
}
