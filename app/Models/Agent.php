<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'avatar_path',
        'description',
        'system_prompt',
        'temperature',
        'openrouter_model_id',
        'capabilities',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'decimal:2',
            'capabilities' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function knowledgeSources(): HasMany
    {
        return $this->hasMany(KnowledgeSource::class);
    }

    public function knowledgeChunks(): HasMany
    {
        return $this->hasMany(KnowledgeChunk::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function hasCapability(string $capability): bool
    {
        return in_array($capability, $this->capabilities ?? []);
    }
}
