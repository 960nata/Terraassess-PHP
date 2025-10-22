<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';

    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'ph',
        'conductivity',
        'nitrogen',
        'phosphorus',
        'potassium',
        'recorded_at'
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'ph' => 'decimal:2',
        'conductivity' => 'decimal:2',
        'nitrogen' => 'integer',
        'phosphorus' => 'integer',
        'potassium' => 'integer',
        'recorded_at' => 'datetime'
    ];

    // Relasi dengan device
    public function device()
    {
        return $this->belongsTo(IotDevice::class, 'device_id', 'device_id');
    }

    // Scope untuk filter berdasarkan device
    public function scopeForDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }

    // Scope untuk data terbaru
    public function scopeLatest($query, $limit = 100)
    {
        return $query->orderBy('recorded_at', 'desc')->limit($limit);
    }
}