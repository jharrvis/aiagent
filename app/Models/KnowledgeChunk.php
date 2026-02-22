<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class KnowledgeChunk extends Model
{
    protected $fillable = [
        'agent_id',
        'knowledge_source_id',
        'chunk_text',
        'embedding',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function knowledgeSource(): BelongsTo
    {
        return $this->belongsTo(KnowledgeSource::class);
    }

    public static function searchSimilar(int $agentId, array $queryEmbedding, int $topK = 5): \Illuminate\Support\Collection
    {
        $embeddingString = '[' . implode(',', $queryEmbedding) . ']';
        
        return DB::table('knowledge_chunks')
            ->where('agent_id', $agentId)
            ->selectRaw('*, embedding <=> ?::vector as distance', [$embeddingString])
            ->orderBy('distance')
            ->limit($topK)
            ->get();
    }

    public function setEmbeddingAttribute(array $value): void
    {
        $this->attributes['embedding'] = '[' . implode(',', $value) . ']';
    }
}
