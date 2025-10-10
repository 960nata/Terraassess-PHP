<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_task_id',
        'student_id',
        'is_leader',
        'joined_at'
    ];

    protected $casts = [
        'is_leader' => 'boolean',
        'joined_at' => 'datetime'
    ];

    public function groupTask(): BelongsTo
    {
        return $this->belongsTo(GroupTask::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function evaluations()
    {
        return $this->hasMany(GroupEvaluation::class, 'evaluated_id', 'student_id')
            ->where('group_task_id', $this->group_task_id);
    }

    public function evaluationsGiven()
    {
        return $this->hasMany(GroupEvaluation::class, 'evaluator_id', 'student_id')
            ->where('group_task_id', $this->group_task_id);
    }
}
