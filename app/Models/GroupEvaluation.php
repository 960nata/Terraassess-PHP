<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_task_id',
        'evaluator_id',
        'evaluated_id',
        'rating',
        'points',
        'comment'
    ];

    const RATING_POINTS = [
        'kurang_baik' => 1,
        'cukup_baik' => 2,
        'baik' => 3,
        'sangat_baik' => 4
    ];

    const RATING_LABELS = [
        'kurang_baik' => 'Kurang Baik',
        'cukup_baik' => 'Cukup Baik',
        'baik' => 'Baik',
        'sangat_baik' => 'Sangat Baik'
    ];

    public function groupTask(): BelongsTo
    {
        return $this->belongsTo(GroupTask::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_id');
    }

    public function getRatingLabelAttribute()
    {
        return self::RATING_LABELS[$this->rating] ?? $this->rating;
    }

    public static function getPointsForRating($rating)
    {
        return self::RATING_POINTS[$rating] ?? 0;
    }
}
