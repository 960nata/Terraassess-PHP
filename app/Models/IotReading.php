<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IotReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'soil_temperature',
        'soil_humus',
        'soil_moisture',
        'device_id',
        'location',
        'notes',
        'raw_data',
        'timestamp'
    ];

    protected $casts = [
        'raw_data' => 'array',
        'timestamp' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    // Accessors
    public function getFormattedSoilTemperatureAttribute()
    {
        return number_format($this->soil_temperature, 1) . '°C';
    }

    public function getFormattedSoilHumusAttribute()
    {
        return number_format($this->soil_humus, 1) . '%';
    }

    public function getFormattedSoilMoistureAttribute()
    {
        return number_format($this->soil_moisture, 1) . '%';
    }

    public function getSoilQualityStatusAttribute()
    {
        $temp = $this->soil_temperature;
        $humus = $this->soil_humus;
        $moisture = $this->soil_moisture;

        // Simple quality assessment based on typical soil conditions
        $score = 0;

        // Temperature assessment (optimal: 20-30°C)
        if ($temp >= 20 && $temp <= 30) {
            $score += 3;
        } elseif ($temp >= 15 && $temp <= 35) {
            $score += 2;
        } elseif ($temp >= 10 && $temp <= 40) {
            $score += 1;
        }

        // Humus assessment (optimal: 3-8%)
        if ($humus >= 3 && $humus <= 8) {
            $score += 3;
        } elseif ($humus >= 2 && $humus <= 10) {
            $score += 2;
        } elseif ($humus >= 1 && $humus <= 15) {
            $score += 1;
        }

        // Moisture assessment (optimal: 40-70%)
        if ($moisture >= 40 && $moisture <= 70) {
            $score += 3;
        } elseif ($moisture >= 30 && $moisture <= 80) {
            $score += 2;
        } elseif ($moisture >= 20 && $moisture <= 90) {
            $score += 1;
        }

        if ($score >= 8) return 'Excellent';
        if ($score >= 6) return 'Good';
        if ($score >= 4) return 'Fair';
        if ($score >= 2) return 'Poor';
        return 'Very Poor';
    }

    public function getSoilQualityColorAttribute()
    {
        $status = $this->soil_quality_status;

        $colors = [
            'Excellent' => 'success',
            'Good' => 'primary',
            'Fair' => 'warning',
            'Poor' => 'info',
            'Very Poor' => 'danger'
        ];

        return $colors[$status] ?? 'secondary';
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('timestamp', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('timestamp', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('timestamp', now()->month)
                    ->whereYear('timestamp', now()->year);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Methods
    public function getTimeAgo()
    {
        return $this->timestamp ? $this->timestamp->diffForHumans() : 'Unknown';
    }

    public function isRecent()
    {
        if (!$this->timestamp) {
            return false;
        }

        return $this->timestamp->gt(now()->subHours(1));
    }

    public function getQualityScore()
    {
        $temp = $this->soil_temperature;
        $humus = $this->soil_humus;
        $moisture = $this->soil_moisture;

        $score = 0;

        // Temperature score (0-3)
        if ($temp >= 20 && $temp <= 30) {
            $score += 3;
        } elseif ($temp >= 15 && $temp <= 35) {
            $score += 2;
        } elseif ($temp >= 10 && $temp <= 40) {
            $score += 1;
        }

        // Humus score (0-3)
        if ($humus >= 3 && $humus <= 8) {
            $score += 3;
        } elseif ($humus >= 2 && $humus <= 10) {
            $score += 2;
        } elseif ($humus >= 1 && $humus <= 15) {
            $score += 1;
        }

        // Moisture score (0-3)
        if ($moisture >= 40 && $moisture <= 70) {
            $score += 3;
        } elseif ($moisture >= 30 && $moisture <= 80) {
            $score += 2;
        } elseif ($moisture >= 20 && $moisture <= 90) {
            $score += 1;
        }

        return $score;
    }

    public function getQualityPercentage()
    {
        $score = $this->getQualityScore();
        return round(($score / 9) * 100, 1);
    }
}