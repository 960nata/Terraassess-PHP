<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'thumbnail_path',
        'youtube_url',
        'description',
        'teacher_id',
        'class_id',
        'subject_id',
        'status',
        'views'
    ];

    protected $casts = [
        'views' => 'integer'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'subject_id');
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return null;
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'document' => 'fas fa-file-pdf',
            'video' => 'fas fa-video',
            'image' => 'fas fa-image',
            'audio' => 'fas fa-volume-up',
            'text' => 'fas fa-file-alt',
            default => 'fas fa-file'
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'document' => 'danger',
            'video' => 'primary',
            'image' => 'success',
            'audio' => 'warning',
            'text' => 'info',
            default => 'secondary'
        };
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
