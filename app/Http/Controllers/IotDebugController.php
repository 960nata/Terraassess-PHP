<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IotDebugController extends Controller
{
    /**
     * Display IoT debugging page
     */
    public function index()
    {
        // Get debug user (create if not exists)
        $debugUser = $this->getOrCreateDebugUser();
        
        // Get recent sensor data from debug sessions
        $recentData = IotSensorData::with(['device'])
            ->where('notes', 'like', '%Debug Mode%')
            ->latest('measured_at')
            ->limit(20)
            ->get();
        
        // Get all debug devices
        $devices = IotDevice::where('name', 'like', '%Debug%')
            ->orWhere('name', 'like', '%USB%')
            ->get();
        
        return view('iot-debug', compact('recentData', 'devices', 'debugUser'))
            ->with('title', 'IoT Debugging Tool');
    }

    /**
     * Store sensor data from debug session
     */
    public function storeSensorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'temperature' => 'nullable|numeric|between:-50,100',
            'humidity' => 'nullable|numeric|between:0,100',
            'soil_moisture' => 'nullable|numeric|between:0,100',
            'ph_level' => 'nullable|numeric|between:0,14',
            'nutrient_level' => 'nullable|numeric|between:0,100',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get debug user
            $debugUser = $this->getOrCreateDebugUser();
            
            // Find or create device
            $device = IotDevice::where('device_id', $request->device_id)->first();
            if (!$device) {
                $device = IotDevice::create([
                    'name' => 'Debug USB Device - ' . $request->device_id,
                    'device_id' => $request->device_id,
                    'bluetooth_address' => 'USB_DEBUG',
                    'device_type' => 'soil_sensor',
                    'status' => 'online'
                ]);
            } else {
                $device->updateStatus('online');
            }

            // Store sensor data
            $sensorData = IotSensorData::create([
                'device_id' => $device->id,
                'kelas_id' => 1, // Default class for debug
                'user_id' => $debugUser->id,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture,
                'ph_level' => $request->ph_level,
                'nutrient_level' => $request->nutrient_level,
                'location' => $request->location ?? 'Debug Area',
                'notes' => ($request->notes ?? '') . ' - Debug Mode',
                'raw_data' => $request->all(),
                'measured_at' => now()
            ]);

            Log::info('IoT debug sensor data stored', [
                'device_id' => $device->id,
                'sensor_data_id' => $sensorData->id,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sensor berhasil disimpan',
                'data' => $sensorData->load('device')
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing IoT debug sensor data', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sensor data with filters
     */
    public function getSensorData(Request $request)
    {
        $query = IotSensorData::with(['device'])
            ->where('notes', 'like', '%Debug Mode%');

        // Filter by date range
        if ($request->from_date) {
            $query->where('measured_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $query->where('measured_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        // Filter by device
        if ($request->device_id) {
            $query->whereHas('device', function($q) use ($request) {
                $q->where('device_id', $request->device_id);
            });
        }

        // Filter by sensor type
        if ($request->sensor_type) {
            switch ($request->sensor_type) {
                case 'temperature':
                    $query->whereNotNull('temperature');
                    break;
                case 'humidity':
                    $query->whereNotNull('humidity');
                    break;
                case 'soil_moisture':
                    $query->whereNotNull('soil_moisture');
                    break;
            }
        }

        $data = $query->latest('measured_at')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total()
            ]
        ]);
    }

    /**
     * Clear all debug data
     */
    public function clearDebugData()
    {
        try {
            $deleted = IotSensorData::where('notes', 'like', '%Debug Mode%')->delete();
            
            Log::info('IoT debug data cleared', ['deleted_count' => $deleted]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deleted} data debug"
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing IoT debug data', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data debug: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get or create debug user
     */
    private function getOrCreateDebugUser()
    {
        $debugUser = User::where('email', 'iot-debug@system.local')->first();
        
        if (!$debugUser) {
            $debugUser = User::create([
                'name' => 'IoT Debug System',
                'email' => 'iot-debug@system.local',
                'password' => bcrypt('debug123'),
                'roles_id' => 1, // Super Admin role
                'kelas_id' => 1,
                'nis_nip' => 'DEBUG001',
                'email_verified_at' => now()
            ]);
            
            Log::info('IoT Debug user created', ['user_id' => $debugUser->id]);
        }
        
        return $debugUser;
    }
}
