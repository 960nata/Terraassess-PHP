<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
        'is_internal_note'
    ];

    protected $casts = [
        'is_internal_note' => 'boolean',
    ];

    // Relationships
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    // Accessors
    public function getIsPublicAttribute()
    {
        return !$this->is_internal_note;
    }

    public function getReplyTypeAttribute()
    {
        return $this->is_internal_note ? 'Internal' : 'Public';
    }

    public function getReplyTypeColorAttribute()
    {
        return $this->is_internal_note ? 'warning' : 'primary';
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_internal_note', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal_note', true);
    }

    // Methods
    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }

    public function getShortMessage($length = 100)
    {
        return \Str::limit($this->message, $length);
    }
}