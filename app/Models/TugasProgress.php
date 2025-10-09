<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_id',
        'user_id',
        'status',
        'progress_percentage',
        'started_at',
        'submitted_at',
        'graded_at',
        'final_score',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method untuk update progress
    public function updateProgress($percentage, $status = null)
    {
        $this->progress_percentage = min(100, max(0, $percentage));
        
        if ($status) {
            $this->status = $status;
        }
        
        if ($status === 'in_progress' && !$this->started_at) {
            $this->started_at = now();
        }
        
        if ($status === 'submitted' && !$this->submitted_at) {
            $this->submitted_at = now();
        }
        
        if ($status === 'graded' && !$this->graded_at) {
            $this->graded_at = now();
        }
        
        $this->save();
    }
}