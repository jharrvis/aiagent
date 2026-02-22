<?php

namespace App\Services;

use App\Models\KnowledgeChunk;
use Illuminate\Support\Facades\Log;

class RAGService
{
    public function __construct(
        private LLMService $llmService
    ) {}

    public function retrieve(int $agentId, string $query, int $topK = 5): ?string
    {
        try {
            $queryEmbedding = $this->llmService->generateEmbedding($query);
            
            $chunks = KnowledgeChunk::searchSimilar($agentId, $queryEmbedding, $topK);

            if ($chunks->isEmpty()) {
                return null;
            }

            return $chunks->pluck('chunk_text')->join("\n\n");
        } catch (\Exception $e) {
            Log::error('RAG retrieval failed', [
                'agent_id' => $agentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
