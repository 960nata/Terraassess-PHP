<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IotDevice;
use App\Models\IotDeviceStatus;
use Illuminate\Support\Facades\Log;

class IotDeviceStatusController extends Controller
{
    /**
     * Update device status from ESP8266 heartbeat
     */
    public function updateStatus(Request $request)
    {
        try {
            $deviceId = $request->input('device_id');
            $device = IotDevice::find($deviceId);
            
            if (!$device) {
                Log::warning('Device status update failed: Device not found', ['device_id' => $deviceId]);
                return response()->json(['error' => 'Device not found'], 404);
            }
            
            $status = IotDeviceStatus::updateOrCreate(
                ['device_id' => $deviceId],
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
            Log::error('Error updating device status: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Failed to update device status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get device status for web interface
     */
    public function getStatus($deviceId)
    {
        try {
            $status = IotDeviceStatus::where('device_id', $deviceId)->first();
            
            if (!$status) {
                return response()->json([
                    'is_online' => false,
                    'message' => 'Device never connected',
                    'last_seen' => null,
                    'last_seen_human' => null
                ]);
            }
            
            // Check if last_seen is within 2 minutes
            $isOnline = $status->last_seen && 
                        $status->last_seen->gt(now()->subMinutes(2));
            
            return response()->json([
                'is_online' => $isOnline,
                'wifi_ssid' => $status->wifi_ssid,
                'wifi_rssi' => $status->wifi_rssi,
                'wifi_signal_description' => $status->wifi_signal_description,
                'ip_address' => $status->ip_address,
                'last_seen' => $status->last_seen,
                'last_seen_human' => $status->last_seen_human,
                'system_info' => $status->system_info,
                'device_id' => $deviceId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting device status: ' . $e->getMessage(), [
                'device_id' => $deviceId
            ]);
            
            return response()->json([
                'error' => 'Failed to get device status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all devices status (for admin dashboard)
     */
    public function getAllDevicesStatus()
    {
        try {
            $devices = IotDevice::with('status')->get();
            
            $devicesStatus = $devices->map(function ($device) {
                $status = IotDeviceStatus::where('device_id', $device->id)->first();
                $isOnline = $status && $status->last_seen && 
                           $status->last_seen->gt(now()->subMinutes(2));
                
                return [
                    'device_id' => $device->device_id,
                    'device_name' => $device->name,
                    'is_online' => $isOnline,
                    'wifi_ssid' => $status ? $status->wifi_ssid : null,
                    'wifi_rssi' => $status ? $status->wifi_rssi : null,
                    'ip_address' => $status ? $status->ip_address : null,
                    'last_seen' => $status ? $status->last_seen : null,
                    'last_seen_human' => $status ? $status->last_seen_human : null
                ];
            });
            
            return response()->json([
                'devices' => $devicesStatus,
                'total_devices' => $devices->count(),
                'online_devices' => $devicesStatus->where('is_online', true)->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting all devices status: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to get devices status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}