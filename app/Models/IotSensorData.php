<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IotSensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'kelas_id',
        'user_id',
        'temperature',
        'humidity',
        'soil_moisture',
        'ph_level',
        'nutrient_level',
        'location',
        'notes',
        'raw_data',
        'measured_at'
    ];

    protected $casts = [
        'measured_at' => 'datetime',
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'soil_moisture' => 'decimal:2',
        'ph_level' => 'decimal:2',
        'nutrient_level' => 'decimal:2',
        'raw_data' => 'array'
    ];

    /**
     * Get the device that owns this sensor data
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(IotDevice::class, 'device_id');
    }

    /**
     * Get the research project that owns this sensor data
     */
    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    /**
     * Get the class that owns this sensor data
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the user that owns this sensor data
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get formatted temperature
     */
    public function getFormattedTemperatureAttribute(): string
    {
        return $this->temperature . '°C';
    }

    /**
     * Get formatted humidity
     */
    public function getFormattedHumidityAttribute(): string
    {
        return $this->humidity . '%';
    }

    /**
     * Get formatted soil moisture
     */
    public function getFormattedSoilMoistureAttribute(): string
    {
        return $this->soil_moisture . '%';
    }

    /**
     * Get soil quality status based on readings
     */
    public function getSoilQualityStatusAttribute(): string
    {
        $temp = $this->temperature;
        $humidity = $this->humidity;
        $moisture = $this->soil_moisture;

        // Ideal ranges for soil
        if ($temp >= 20 && $temp <= 30 && 
            $humidity >= 40 && $humidity <= 70 && 
            $moisture >= 30 && $moisture <= 60) {
            return 'excellent';
        } elseif ($temp >= 15 && $temp <= 35 && 
                 $humidity >= 30 && $humidity <= 80 && 
                 $moisture >= 20 && $moisture <= 70) {
            return 'good';
        } else {
            return 'needs_attention';
        }
    }

    /**
     * Get soil quality status label
     */
    public function getSoilQualityLabelAttribute(): string
    {
        return match($this->soil_quality_status) {
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'needs_attention' => 'Perlu Perhatian',
            default => 'Tidak Diketahui'
        };
    }
}