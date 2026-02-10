<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_id',
        'bluetooth_address',
        'device_type',
        'connection_type',
        'description',
        'location',
        'class_id',
        'platform',
        'status',
        'user_id',
        'device_info',
        'last_seen',
        'data_points',
        'last_connected',
        'last_disconnected',
    ];

    protected $casts = [
        'device_info' => 'array',
        'last_seen' => 'datetime',
        'last_connected' => 'datetime',
        'last_disconnected' => 'datetime',
        'data_points' => 'integer',
    ];

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sensorData()
    {
        return $this->hasMany(IotSensorData::class, 'device_id');
    }

    public function latestSensorData()
    {
        return $this->hasOne(IotSensorData::class, 'device_id')->latest('measured_at');
    }

    public function status()
    {
        return $this->hasOne(IotDeviceStatus::class, 'device_id');
    }

    // Scopes
    public function scopeConnected($query)
    {
        return $query->where('status', 'connected');
    }

    public function scopeDisconnected($query)
    {
        return $query->where('status', 'disconnected');
    }

    public function scopeAdreno($query)
    {
        return $query->where('platform', 'adreno');
    }

    public function scopeByConnectionType($query, $type)
    {
        return $query->where('connection_type', $type);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return $this->status === 'connected' ? 'Terhubung' : 'Terputus';
    }

    public function getConnectionTypeTextAttribute()
    {
        return strtoupper($this->connection_type);
    }

    public function getClassNameAttribute()
    {
        return $this->kelas ? $this->kelas->name : 'Tidak ada kelas';
    }

    // Methods
    public function isConnected()
    {
        return $this->status === 'connected';
    }

    public function isDisconnected()
    {
        return $this->status === 'disconnected';
    }

    public function getUptime()
    {
        if (!$this->last_connected) {
            return 'Tidak pernah terhubung';
        }

        if ($this->isConnected()) {
            return $this->last_connected->diffForHumans();
        }

        return $this->last_disconnected ? $this->last_disconnected->diffForHumans() : 'Tidak diketahui';
    }

    public function incrementDataPoints($points = 1)
    {
        $this->increment('data_points', $points);
    }

    public function resetDataPoints()
    {
        $this->update(['data_points' => 0]);
    }

    public function getDataPointsFormatted()
    {
        $points = $this->data_points ?? 0;
        
        if ($points >= 1000000) {
            return round($points / 1000000, 1) . 'M';
        } elseif ($points >= 1000) {
            return round($points / 1000, 1) . 'K';
        }
        
        return $points;
    }

    public function updateStatus($status)
    {
        $this->update([
            'status' => $status,
            'last_seen' => now()
        ]);
        
        if ($status === 'online') {
            $this->update(['last_connected' => now()]);
        } elseif ($status === 'offline') {
            $this->update(['last_disconnected' => now()]);
        }
    }

    public function isOnline()
    {
        return $this->status === 'online' || $this->status === 'connected';
    }
}