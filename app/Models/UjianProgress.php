<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UjianProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ujian_id',
        'status',
        'started_at',
        'completed_at',
        'time_spent',
        'current_question',
        'total_questions',
        'answered_questions',
        'progress_percentage'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent' => 'integer',
        'progress_percentage' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'not_started' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'submitted' => 'info',
            'graded' => 'primary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'not_started' => 'Belum Dimulai',
            'in_progress' => 'Sedang Mengerjakan',
            'completed' => 'Selesai Dikerjakan',
            'submitted' => 'Sudah Submit',
            'graded' => 'Sudah Dinilai'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getTimeSpentFormattedAttribute()
    {
        if (!$this->time_spent) {
            return '0 menit';
        }

        $hours = floor($this->time_spent / 60);
        $minutes = $this->time_spent % 60;

        if ($hours > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        }

        return $minutes . ' menit';
    }

    public function getProgressBarWidthAttribute()
    {
        return min(100, max(0, $this->progress_percentage));
    }

    // Methods
    public function updateProgress($currentQuestion, $totalQuestions)
    {
        $this->current_question = $currentQuestion;
        $this->total_questions = $totalQuestions;
        $this->answered_questions = $currentQuestion - 1;
        $this->progress_percentage = $totalQuestions > 0 ? (($currentQuestion - 1) / $totalQuestions) * 100 : 0;
        $this->save();
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        
        if ($this->started_at) {
            $this->time_spent = $this->started_at->diffInMinutes(now());
        }
        
        $this->progress_percentage = 100;
        $this->save();
    }

    public function markAsSubmitted()
    {
        $this->status = 'submitted';
        $this->save();
    }

    public function markAsGraded()
    {
        $this->status = 'graded';
        $this->save();
    }

    public function startExam()
    {
        $this->status = 'in_progress';
        $this->started_at = now();
        $this->save();
    }

    public function isOverdue()
    {
        if (!$this->ujian || !$this->ujian->due) {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($this->ujian->due));
    }

    public function getTimeRemaining()
    {
        if (!$this->ujian || !$this->ujian->due) {
            return null;
        }

        $deadline = Carbon::parse($this->ujian->due);
        $now = Carbon::now();

        if ($now->gt($deadline)) {
            return 'Terlambat';
        }

        return $now->diffForHumans($deadline, true);
    }

    // Scopes
    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not_started');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'submitted', 'graded']);
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }
}
