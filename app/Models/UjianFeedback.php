<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UjianFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'user_id',
        'teacher_id',
        'score',
        'max_score',
        'grade',
        'feedback_text',
        'strengths',
        'weaknesses',
        'suggestions',
        'rating',
        'status',
        'graded_at'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'rating' => 'integer',
        'graded_at' => 'datetime'
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Accessors
    public function getPercentageAttribute()
    {
        if (!$this->max_score || $this->max_score == 0) {
            return 0;
        }

        return round(($this->score / $this->max_score) * 100, 2);
    }

    public function getGradeAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Auto calculate grade based on percentage
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'E';
    }

    public function getGradeColorAttribute()
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'success';
        if ($percentage >= 80) return 'primary';
        if ($percentage >= 70) return 'warning';
        if ($percentage >= 60) return 'info';
        return 'danger';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'graded' => 'success',
            'reviewed' => 'info'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Penilaian',
            'graded' => 'Sudah Dinilai',
            'reviewed' => 'Sudah Direview'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="ph-star-fill text-warning"></i>';
            } else {
                $stars .= '<i class="ph-star text-muted"></i>';
            }
        }
        return $stars;
    }

    // Methods
    public function calculateGrade()
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'E';
    }

    public function markAsGraded()
    {
        $this->status = 'graded';
        $this->graded_at = now();
        $this->grade = $this->calculateGrade();
        $this->save();
    }

    public function markAsReviewed()
    {
        $this->status = 'reviewed';
        $this->save();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
