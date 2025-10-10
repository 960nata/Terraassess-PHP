<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'excerpt',
        'type',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    // Static methods
    public static function createForUser($userId, $title, $body, $type = 'info', $excerpt = null, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'excerpt' => $excerpt,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public static function createForMultipleUsers($userIds, $title, $body, $type = 'info', $excerpt = null, $data = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'excerpt' => $excerpt,
                'type' => $type,
                'data' => $data,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        return self::insert($notifications);
    }
}