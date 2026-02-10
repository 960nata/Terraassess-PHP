<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'complaint_reply_id',
        'file_name',
        'file_path',
        'file_type',
        'file_extension',
        'file_size',
        'uploaded_by'
    ];

    // Relationships
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function complaintReply()
    {
        return $this->belongsTo(ComplaintReply::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getIsImageAttribute()
    {
        return str_starts_with($this->file_type, 'image/');
    }

    public function getIsDocumentAttribute()
    {
        return in_array($this->file_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    public function getFileIconAttribute()
    {
        if ($this->is_image) {
            return 'ph-image';
        }

        switch ($this->file_extension) {
            case 'pdf':
                return 'ph-file-pdf';
            case 'doc':
            case 'docx':
                return 'ph-file-doc';
            case 'xls':
            case 'xlsx':
                return 'ph-file-xls';
            default:
                return 'ph-file';
        }
    }

    public function getFileIconColorAttribute()
    {
        if ($this->is_image) {
            return 'text-green-500';
        }

        switch ($this->file_extension) {
            case 'pdf':
                return 'text-red-500';
            case 'doc':
            case 'docx':
                return 'text-blue-500';
            case 'xls':
            case 'xlsx':
                return 'text-green-600';
            default:
                return 'text-gray-500';
        }
    }

    // Methods
    public function getThumbnailUrl()
    {
        if ($this->is_image) {
            return $this->file_url;
        }
        return null;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    public function download()
    {
        if (Storage::exists($this->file_path)) {
            return Storage::download($this->file_path, $this->file_name);
        }
        return null;
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('file_type', 'like', 'image/%');
    }

    public function scopeDocuments($query)
    {
        return $query->whereNot('file_type', 'like', 'image/%');
    }

    public function scopeByComplaint($query, $complaintId)
    {
        return $query->where('complaint_id', $complaintId);
    }

    public function scopeByReply($query, $replyId)
    {
        return $query->where('complaint_reply_id', $replyId);
    }
}