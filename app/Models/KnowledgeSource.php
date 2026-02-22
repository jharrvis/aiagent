<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KnowledgeSource extends Model
{
    protected $fillable = [
        'agent_id',
        'file_path',
        'original_filename',
        'status',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(KnowledgeChunk::class);
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
