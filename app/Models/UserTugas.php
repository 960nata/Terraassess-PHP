<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserTugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tugas_id',
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

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Accessors
    public function getFormattedJawabanAttribute()
    {
        return nl2br(e($this->jawaban));
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'submitted' => 'info',
            'completed' => 'success',
            'graded' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Belum Dikerjakan',
            'submitted' => 'Sudah Dikumpulkan',
            'completed' => 'Sudah Dinilai',
            'graded' => 'Sudah Dinilai'
        ];

        return $texts[$this->status] ?? 'Unknown';
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

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    // Methods
    public function isOverdue()
    {
        if (!$this->tugas || !$this->tugas->due) {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($this->tugas->due));
    }

    public function getTimeRemaining()
    {
        if (!$this->tugas || !$this->tugas->due) {
            return null;
        }

        $deadline = Carbon::parse($this->tugas->due);
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
}