<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'message',
        'status',
        'priority',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function replies()
    {
        return $this->hasMany(ComplaintReply::class);
    }

    public function publicReplies()
    {
        return $this->hasMany(ComplaintReply::class)->where('is_internal_note', false);
    }

    public function internalReplies()
    {
        return $this->hasMany(ComplaintReply::class)->where('is_internal_note', true);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getPriorityTextAttribute()
    {
        $priorities = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi'
        ];

        return $priorities[$this->priority] ?? 'Unknown';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger'
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    public function getCategoryTextAttribute()
    {
        $categories = [
            'akademik' => 'Akademik',
            'fasilitas' => 'Fasilitas',
            'bullying' => 'Bullying',
            'lainnya' => 'Lainnya'
        ];

        return $categories[$this->category] ?? 'Unknown';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Methods
    public function isResolved()
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    public function canBeReplied()
    {
        return !in_array($this->status, ['closed']);
    }

    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }

    public function getLastReplyTime()
    {
        $lastReply = $this->replies()->latest()->first();
        return $lastReply ? $lastReply->created_at->diffForHumans() : null;
    }
}