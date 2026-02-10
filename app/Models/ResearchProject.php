<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'description',
        'kelas_id',
        'teacher_id',
        'status',
        'start_date',
        'end_date',
        'research_parameters',
        'conclusion'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    /**
     * Get the class that owns this research project
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the teacher who owns this research project
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all sensor data for this research project
     */
    public function sensorData(): HasMany
    {
        return $this->hasMany(IotSensorData::class, 'research_project_id');
    }

    /**
     * Get project duration in days
     */
    public function getDurationAttribute(): int
    {
        if (!$this->end_date) {
            return $this->start_date->diffInDays(now());
        }
        
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get project status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'paused' => 'Dijeda',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get project status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'completed' => 'badge-primary',
            'paused' => 'badge-warning',
            default => 'badge-secondary'
        };
    }
}
