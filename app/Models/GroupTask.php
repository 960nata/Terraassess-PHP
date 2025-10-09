<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructions',
        'teacher_id',
        'class_id',
        'subject_id',
        'start_date',
        'end_date',
        'max_members',
        'min_members',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Class::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(GroupEvaluation::class);
    }

    public function getLeaderAttribute()
    {
        return $this->members()->where('is_leader', true)->first();
    }

    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    public function isFull()
    {
        return $this->member_count >= $this->max_members;
    }

    public function canJoin()
    {
        return $this->is_active && !$this->isFull() && now()->between($this->start_date, $this->end_date);
    }
}
