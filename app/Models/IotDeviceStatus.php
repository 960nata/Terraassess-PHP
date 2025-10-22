<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IotDeviceStatus extends Model
{
    use HasFactory;

    protected $table = 'iot_device_status';

    protected $fillable = [
        'device_id',
        'is_online',
        'wifi_ssid',
        'wifi_rssi',
        'ip_address',
        'last_seen',
        'system_info'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
        'system_info' => 'array'
    ];

    /**
     * Get the device that owns the status
     */
    public function device()
    {
        return $this->belongsTo(IotDevice::class);
    }

    /**
     * Check if device is currently online (last seen within 2 minutes)
     */
    public function getIsCurrentlyOnlineAttribute()
    {
        if (!$this->last_seen) {
            return false;
        }

        return $this->last_seen->gt(now()->subMinutes(2));
    }

    /**
     * Get human readable last seen time
     */
    public function getLastSeenHumanAttribute()
    {
        if (!$this->last_seen) {
            return null;
        }

        return $this->last_seen->diffForHumans();
    }

    /**
     * Get WiFi signal strength description
     */
    public function getWifiSignalDescriptionAttribute()
    {
        if (!$this->wifi_rssi) {
            return 'Unknown';
        }

        if ($this->wifi_rssi >= -30) {
            return 'Excellent';
        } elseif ($this->wifi_rssi >= -50) {
            return 'Good';
        } elseif ($this->wifi_rssi >= -70) {
            return 'Fair';
        } else {
            return 'Poor';
        }
    }
}