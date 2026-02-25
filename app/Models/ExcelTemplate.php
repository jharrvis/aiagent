<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExcelTemplate extends Model
{
    protected $fillable = [
        'agent_id',
        'name',
        'description',
        'file_path',
        'original_filename',
        'category',
        'variables',
        'sheet_mappings',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'sheet_mappings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getFilePathAttribute($value): string
    {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }

    public function getOriginalFilenameAttribute($value): string
    {
        return $value;
    }
}
