<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ujian_id',
        'status',
        'nilai'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'graded' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Sudah Dikerjakan',
            'graded' => 'Sudah Dinilai'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getFormattedJawabanAttribute()
    {
        if (!$this->jawaban) {
            return [];
        }

        return $this->jawaban;
    }

    public function getPercentageAttribute()
    {
        if (!$this->ujian || !$this->ujian->soal) {
            return 0;
        }

        $totalSoal = $this->ujian->soal->count();
        if ($totalSoal == 0) {
            return 0;
        }

        return round(($this->skor / $totalSoal) * 100, 2);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'graded']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Methods
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

    public function getGrade()
    {
        if (!$this->nilai) {
            return null;
        }

        if ($this->nilai >= 90) return 'A';
        if ($this->nilai >= 80) return 'B';
        if ($this->nilai >= 70) return 'C';
        if ($this->nilai >= 60) return 'D';
        return 'E';
    }

    public function getGradeColor()
    {
        if (!$this->nilai) {
            return 'secondary';
        }

        if ($this->nilai >= 90) return 'success';
        if ($this->nilai >= 80) return 'primary';
        if ($this->nilai >= 70) return 'warning';
        if ($this->nilai >= 60) return 'info';
        return 'danger';
    }

    public function getCorrectAnswers()
    {
        if (!$this->ujian || !$this->ujian->soal) {
            return 0;
        }

        $correct = 0;
        $jawaban = $this->jawaban ?? [];

        foreach ($this->ujian->soal as $soal) {
            if (isset($jawaban[$soal->id]) && $jawaban[$soal->id] == $soal->jawaban_benar) {
                $correct++;
            }
        }

        return $correct;
    }

    public function getWrongAnswers()
    {
        if (!$this->ujian || !$this->ujian->soal) {
            return 0;
        }

        return $this->ujian->soal->count() - $this->getCorrectAnswers();
    }

    public function getUnanswered()
    {
        if (!$this->ujian || !$this->ujian->soal) {
            return 0;
        }

        $jawaban = $this->jawaban ?? [];
        $answered = count($jawaban);

        return $this->ujian->soal->count() - $answered;
    }
}