<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\IotDeviceStatus;
use App\Services\ThingsBoardService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class IotDeviceController extends Controller
{
    protected $thingsBoardService;

    public function __construct(ThingsBoardService $thingsBoardService)
    {
        $this->thingsBoardService = $thingsBoardService;
    }

    /**
     * Show device dashboard for specific device ID
     */
    public function showDeviceDashboard($deviceId)
    {
        try {
            // Find device in database
            $device = IotDevice::where('device_id', $deviceId)->first();
            
            if (!$device) {
                return view('iot.device-not-found', compact('deviceId'));
            }

            // Get latest sensor data
            $latestData = IotSensorData::where('device_id', $deviceId)
                ->latest('measured_at')
                ->first();

            // Get device status
            $deviceStatus = IotDeviceStatus::where('device_id', $deviceId)->first();

            // Get historical data (last 24 hours)
            $historicalData = IotSensorData::where('device_id', $deviceId)
                ->where('measured_at', '>=', now()->subHours(24))
                ->orderBy('measured_at', 'asc')
                ->get();

            // Prepare chart data
            $chartData = $this->prepareChartData($historicalData);

            return view('iot.device-dashboard', compact(
                'device',
                'latestData',
                'deviceStatus',
                'historicalData',
                'chartData'
            ));

        } catch (\Exception $e) {
            Log::error('Error showing device dashboard: ' . $e->getMessage(), [
                'device_id' => $deviceId,
                'error' => $e->getTraceAsString()
            ]);

            return view('iot.device-error', [
                'deviceId' => $deviceId,
                'error' => 'Failed to load device dashboard'
            ]);
        }
    }

    /**
     * Get real-time data for device (AJAX endpoint)
     */
    public function getDeviceRealTimeData($deviceId)
    {
        try {
            // Check cache first (5 seconds cache)
            $cacheKey = "device_realtime_{$deviceId}";
            $cachedData = Cache::get($cacheKey);
            
            if ($cachedData) {
                return response()->json($cachedData);
            }

            // Get latest data from database
            $latestData = IotSensorData::where('device_id', $deviceId)
                ->latest('measured_at')
                ->first();

            // Get device status
            $deviceStatus = IotDeviceStatus::where('device_id', $deviceId)->first();

            // Try to get fresh data from ThingsBoard
            $thingsBoardData = null;
            try {
                $thingsBoardData = $this->thingsBoardService->getDeviceLatestData($deviceId);
            } catch (\Exception $e) {
                Log::warning('Failed to get ThingsBoard data: ' . $e->getMessage());
            }

            $responseData = [
                'success' => true,
                'device_id' => $deviceId,
                'timestamp' => now()->toISOString(),
                'database_data' => $latestData,
                'device_status' => $deviceStatus,
                'thingsboard_data' => $thingsBoardData,
                'is_online' => $deviceStatus ? $deviceStatus->is_online : false,
                'last_seen' => $deviceStatus ? $deviceStatus->last_seen : null
            ];

            // Cache for 5 seconds
            Cache::put($cacheKey, $responseData, 5);

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Error getting real-time data: ' . $e->getMessage(), [
                'device_id' => $deviceId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get real-time data',
                'device_id' => $deviceId
            ], 500);
        }
    }

    /**
     * Get historical data for device
     */
    public function getDeviceHistory($deviceId, Request $request)
    {
        try {
            $hours = $request->get('hours', 24);
            $limit = $request->get('limit', 100);

            $historicalData = IotSensorData::where('device_id', $deviceId)
                ->where('measured_at', '>=', now()->subHours($hours))
                ->orderBy('measured_at', 'desc')
                ->limit($limit)
                ->get();

            $chartData = $this->prepareChartData($historicalData->reverse());

            return response()->json([
                'success' => true,
                'device_id' => $deviceId,
                'hours' => $hours,
                'data' => $historicalData,
                'chart_data' => $chartData,
                'count' => $historicalData->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting device history: ' . $e->getMessage(), [
                'device_id' => $deviceId,
                'hours' => $hours ?? 24
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get historical data',
                'device_id' => $deviceId
            ], 500);
        }
    }

    /**
     * Sync device data from ThingsBoard
     */
    public function syncDeviceFromThingsBoard($deviceId)
    {
        try {
            // Get latest data from ThingsBoard
            $thingsBoardData = $this->thingsBoardService->getDeviceLatestData($deviceId);
            
            if (!$thingsBoardData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available from ThingsBoard'
                ]);
            }

            // Save to database
            $sensorData = IotSensorData::create([
                'device_id' => $deviceId,
                'soil_temperature' => $thingsBoardData['temperature'] ?? null,
                'soil_moisture' => $thingsBoardData['humidity'] ?? null,
                'soil_conductivity' => $thingsBoardData['conductivity'] ?? null,
                'soil_ph' => $thingsBoardData['ph'] ?? null,
                'nitrogen' => $thingsBoardData['nitrogen'] ?? null,
                'phosphorus' => $thingsBoardData['phosphorus'] ?? null,
                'potassium' => $thingsBoardData['potassium'] ?? null,
                'measured_at' => now()
            ]);

            // Update device status
            IotDeviceStatus::updateOrCreate(
                ['device_id' => $deviceId],
                [
                    'is_online' => true,
                    'last_seen' => now(),
                    'system_info' => [
                        'last_sync' => now()->toISOString(),
                        'data_source' => 'thingsboard'
                    ]
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data synced successfully',
                'data' => $sensorData
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing from ThingsBoard: ' . $e->getMessage(), [
                'device_id' => $deviceId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to sync from ThingsBoard',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update device status from heartbeat
     */
    public function updateDeviceStatus(Request $request)
    {
        try {
            $deviceId = $request->input('device_id');
            
            if (!$deviceId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Device ID is required'
                ], 400);
            }

            // Find or create device
            $device = IotDevice::where('device_id', $deviceId)->first();
            
            if (!$device) {
                // Create device if not exists
                $device = IotDevice::create([
                    'name' => 'ESP8266 NPK Sensor',
                    'device_id' => $deviceId,
                    'device_type' => 'soil_sensor',
                    'status' => 'connected',
                    'user_id' => 1, // Default user
                    'device_info' => [
                        'platform' => 'thingsboard',
                        'token' => 'KddDFrocSfdHGumbd6Jz',
                        'server' => 'demo.thingsboard.io'
                    ]
                ]);
            }

            // Update device status
            $deviceStatus = IotDeviceStatus::updateOrCreate(
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
                        'firmware_version' => $request->input('firmware_version', '1.0.0')
                    ]
                ]
            );

            // Update device last_seen
            $device->update(['last_seen' => now()]);

            Log::info('Device status updated', [
                'device_id' => $deviceId,
                'is_online' => $deviceStatus->is_online,
                'ip_address' => $deviceStatus->ip_address
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device status updated successfully',
                'device_id' => $deviceId
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating device status: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update device status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare chart data for frontend
     */
    private function prepareChartData($data)
    {
        $chartData = [
            'labels' => [],
            'temperature' => [],
            'humidity' => [],
            'ph' => [],
            'conductivity' => [],
            'nitrogen' => [],
            'phosphorus' => [],
            'potassium' => []
        ];

        foreach ($data as $item) {
            $chartData['labels'][] = $item->measured_at->format('H:i');
            $chartData['temperature'][] = $item->soil_temperature ?? 0;
            $chartData['humidity'][] = $item->soil_moisture ?? 0;
            $chartData['ph'][] = $item->soil_ph ?? 0;
            $chartData['conductivity'][] = $item->soil_conductivity ?? 0;
            $chartData['nitrogen'][] = $item->nitrogen ?? 0;
            $chartData['phosphorus'][] = $item->phosphorus ?? 0;
            $chartData['potassium'][] = $item->potassium ?? 0;
        }

        return $chartData;
    }

    /**
     * Get device statistics
     */
    public function getDeviceStatistics($deviceId)
    {
        try {
            $stats = IotSensorData::where('device_id', $deviceId)
                ->where('measured_at', '>=', now()->subDays(7))
                ->selectRaw('
                    AVG(soil_temperature) as avg_temperature,
                    AVG(soil_moisture) as avg_humidity,
                    AVG(soil_ph) as avg_ph,
                    AVG(soil_conductivity) as avg_conductivity,
                    AVG(nitrogen) as avg_nitrogen,
                    AVG(phosphorus) as avg_phosphorus,
                    AVG(potassium) as avg_potassium,
                    COUNT(*) as total_readings
                ')
                ->first();

            return response()->json([
                'success' => true,
                'device_id' => $deviceId,
                'statistics' => $stats,
                'period' => '7 days'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting device statistics: ' . $e->getMessage(), [
                'device_id' => $deviceId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get device statistics',
                'device_id' => $deviceId
            ], 500);
        }
    }
}

