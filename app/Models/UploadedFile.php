<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UploadedFile extends Model
{
    protected $fillable = [
        'conversation_id',
        'message_id',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'file_type',
        'analysis_summary',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function getHumanReadableSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->file_type === 'image') {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    public function getContentAttribute(): ?string
    {
        // For text files, return content
        if ($this->file_type === 'text' && $this->file_size < 1024 * 1024) { // Max 1MB
            try {
                return Storage::get($this->file_path);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
