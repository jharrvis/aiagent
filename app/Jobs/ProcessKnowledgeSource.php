<?php

namespace App\Jobs;

use App\Models\KnowledgeSource;
use App\Models\KnowledgeChunk;
use App\Services\LLMService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class ProcessKnowledgeSource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private KnowledgeSource $knowledgeSource
    ) {}

    public function handle(LLMService $llmService): void
    {
        $this->knowledgeSource->update(['status' => 'processing']);

        try {
            $text = $this->extractText();
            $chunks = $this->chunkText($text);

            foreach ($chunks as $index => $chunkText) {
                $embedding = $llmService->generateEmbedding($chunkText);

                KnowledgeChunk::create([
                    'agent_id' => $this->knowledgeSource->agent_id,
                    'knowledge_source_id' => $this->knowledgeSource->id,
                    'chunk_text' => $chunkText,
                    'embedding' => $embedding,
                    'metadata' => [
                        'chunk_index' => $index,
                        'source_file' => $this->knowledgeSource->original_filename,
                    ],
                ]);
            }

            $this->knowledgeSource->update(['status' => 'ready']);
            
            Log::info('Knowledge source processed', [
                'source_id' => $this->knowledgeSource->id,
                'chunks_count' => count($chunks),
            ]);
        } catch (\Exception $e) {
            $this->knowledgeSource->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            Log::error('Knowledge source processing failed', [
                'source_id' => $this->knowledgeSource->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function extractText(): string
    {
        $path = Storage::path($this->knowledgeSource->file_path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return match ($extension) {
            'pdf' => $this->extractFromPdf($path),
            'txt' => file_get_contents($path),
            'docx' => $this->extractFromDocx($path),
            default => throw new \RuntimeException("Unsupported file type: {$extension}"),
        };
    }

    private function extractFromPdf(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($path);
        
        return $pdf->getText();
    }

    private function extractFromDocx(string $path): string
    {
        $content = file_get_contents($path);
        $content = preg_replace('/<[^>]+>/', ' ', $content);
        $content = html_entity_decode($content);
        
        return strip_tags($content);
    }

    private function chunkText(string $text, int $chunkSize = 1000, int $overlap = 200): array
    {
        $words = preg_split('/\s+/', $text);
        $chunks = [];
        $currentChunk = [];
        $currentLength = 0;

        foreach ($words as $word) {
            $wordLength = strlen($word);

            if ($currentLength + $wordLength > $chunkSize && !empty($currentChunk)) {
                $chunks[] = implode(' ', $currentChunk);
                
                $overlapWords = array_slice($currentChunk, -$overlap);
                $currentChunk = $overlapWords;
                $currentLength = array_sum(array_map('strlen', $overlapWords));
            }

            $currentChunk[] = $word;
            $currentLength += $wordLength;
        }

        if (!empty($currentChunk)) {
            $chunks[] = implode(' ', $currentChunk);
        }

        return $chunks;
    }
}
