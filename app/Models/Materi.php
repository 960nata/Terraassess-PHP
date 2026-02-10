<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'file_materi',
        'deskripsi',
        'kelas_mapel_id',
        'isHidden'
    ];

    protected $casts = [
        'isHidden' => 'boolean'
    ];

    // Relationships
    public function kelasMapel()
    {
        return $this->belongsTo(KelasMapel::class);
    }

    // Accessors
    public function getShortContentAttribute()
    {
        return \Str::limit($this->content, 150);
    }

    public function getFileTypeAttribute()
    {
        if (!$this->file_materi) {
            return 'text';
        }

        $extension = strtolower(pathinfo($this->file_materi, PATHINFO_EXTENSION));
        
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];

        if (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        }

        return 'file';
    }

    public function getFileIconAttribute()
    {
        $type = $this->file_type;

        $icons = [
            'video' => 'fas fa-video',
            'document' => 'fas fa-file-alt',
            'image' => 'fas fa-image',
            'file' => 'fas fa-file',
            'text' => 'fas fa-file-text'
        ];

        return $icons[$type] ?? 'fas fa-file';
    }

    public function getFileColorAttribute()
    {
        $type = $this->file_type;

        $colors = [
            'video' => 'text-danger',
            'document' => 'text-primary',
            'image' => 'text-success',
            'file' => 'text-secondary',
            'text' => 'text-info'
        ];

        return $colors[$type] ?? 'text-secondary';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('isHidden', false);
    }

    public function scopeByType($query, $type)
    {
        if ($type === 'video') {
            return $query->where(function($q) {
                $q->where('file_materi', 'like', '%.mp4')
                  ->orWhere('file_materi', 'like', '%.avi')
                  ->orWhere('file_materi', 'like', '%.mov')
                  ->orWhere('file_materi', 'like', '%.wmv')
                  ->orWhere('file_materi', 'like', '%.flv')
                  ->orWhere('file_materi', 'like', '%.webm');
            });
        } elseif ($type === 'document') {
            return $query->where(function($q) {
                $q->where('file_materi', 'like', '%.pdf')
                  ->orWhere('file_materi', 'like', '%.doc')
                  ->orWhere('file_materi', 'like', '%.docx')
                  ->orWhere('file_materi', 'like', '%.ppt')
                  ->orWhere('file_materi', 'like', '%.pptx')
                  ->orWhere('file_materi', 'like', '%.txt');
            });
        }

        return $query;
    }

    // Methods
    public function getFileUrl()
    {
        if (!$this->file_materi) {
            return null;
        }

        return asset('storage/' . $this->file_materi);
    }

    public function getFileSize()
    {
        if (!$this->file_materi) {
            return null;
        }

        $path = storage_path('app/public/' . $this->file_materi);
        
        if (!file_exists($path)) {
            return null;
        }

        $bytes = filesize($path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileExtension()
    {
        if (!$this->file_materi) {
            return null;
        }

        return strtoupper(pathinfo($this->file_materi, PATHINFO_EXTENSION));
    }

    public function isVideo()
    {
        return $this->file_type === 'video';
    }

    public function isDocument()
    {
        return $this->file_type === 'document';
    }

    public function isImage()
    {
        return $this->file_type === 'image';
    }

    public function hasFile()
    {
        return !is_null($this->file_materi);
    }

    public function getDownloadUrl()
    {
        if (!$this->file_materi) {
            return null;
        }

        return route('download.materi', $this->id);
    }
}