<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IotDevice;
use App\Models\IotDeviceStatus;
use Illuminate\Support\Facades\Log;

class Esp8266Controller extends Controller
{
    /**
     * Update device status from ESP8266 heartbeat (no CSRF required)
     */
    public function updateDeviceStatus(Request $request)
    {
        try {
            $deviceId = $request->input('device_id');
            $device = IotDevice::where('device_id', $deviceId)->first();
            
            if (!$device) {
                Log::warning('Device status update failed: Device not found', ['device_id' => $deviceId]);
                return response()->json(['error' => 'Device not found'], 404);
            }
            
            $status = IotDeviceStatus::updateOrCreate(
                ['device_id' => $device->id],
                [
                    'is_online' => $request->input('is_online', true),
                    'wifi_ssid' => $request->input('wifi_ssid'),
                    'wifi_rssi' => $request->input('wifi_rssi'),
                    'ip_address' => $request->input('ip_address'),
                    'last_seen' => now(),
                    'system_info' => [
                        'uptime' => $request->input('uptime'),
                        'free_heap' => $request->input('free_heap'),
                        'chip_id' => $request->input('chip_id'),
                        'firmware_version' => $request->input('firmware_version')
                    ]
                ]
            );
            
            Log::info('Device status updated successfully', [
                'device_id' => $deviceId,
                'is_online' => $status->is_online,
                'ip_address' => $status->ip_address,
                'wifi_ssid' => $status->wifi_ssid
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'device_id' => $deviceId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Device status update failed', [
                'error' => $e->getMessage(),
                'device_id' => $request->input('device_id')
            ]);
            
            return response()->json([
                'error' => 'Failed to update device status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
