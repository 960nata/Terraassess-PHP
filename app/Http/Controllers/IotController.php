<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\ResearchProject;
use App\Models\Kelas;
use App\Services\ThingsBoardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class IotController extends Controller
{
    /**
     * Display IoT dashboard
     */
    public function index()
    {
        $devices = IotDevice::with('latestSensorData')->get();
        $recentData = IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        $kelas = Kelas::all();
        
        return view('menu.pengajar.iot.dashboard', compact('devices', 'recentData', 'kelas'))
            ->with('title', 'IoT Dashboard');
    }

    /**
     * Show device management page
     */
    public function devices()
    {
        $devices = IotDevice::with('latestSensorData')->get();
        return view('menu.pengajar.iot.devices', compact('devices'))
            ->with('title', 'IoT Devices');
    }

    /**
     * Show sensor data page
     */
    public function sensorData(Request $request)
    {
        $query = IotSensorData::with(['device', 'kelas', 'user']);
        
        // Filter by device
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }
        
        // Filter by class
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('measured_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('measured_at', '<=', $request->date_to);
        }
        
        $sensorData = $query->latest('measured_at')->paginate(20);
        $devices = IotDevice::all();
        $kelas = Kelas::all();
        
        return view('menu.pengajar.iot.sensor-data', compact('sensorData', 'devices', 'kelas'))
            ->with('title', 'IoT Sensor Data');
    }

    /**
     * Show research projects page
     */
    public function researchProjects()
    {
        $user = auth()->user();
        
        // Check if user is student or teacher
        if ($user->roles_id == 4) { // Student role
            // Show projects for student's class
            $projects = ResearchProject::with(['kelas', 'pengajar'])
                ->where('kelas_id', $user->kelas_id)
                ->where('status', 'active')
                ->latest()
                ->get();
            
            return view('student.iot-research-projects', compact('projects'))
                ->with('title', 'Research Projects IoT');
        } else {
            // Teacher view - show their own projects
            $projects = ResearchProject::with(['kelas', 'teacher'])
                ->where('teacher_id', auth()->id())
                ->latest()
                ->get();
            
            $kelas = Kelas::all();
            
            return view('menu.pengajar.iot.research-projects', compact('projects', 'kelas'))
                ->with('title', 'Research Projects IoT');
        }
    }

    /**
     * Store sensor data from IoT device
     */
    public function storeSensorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'soil_temperature' => 'required|numeric|between:-50,100',
            'humidity' => 'required|numeric|between:0,100',
            'soil_moisture' => 'required|numeric|between:0,100',
            'ph_level' => 'nullable|numeric|between:0,14',
            'nitrogen' => 'nullable|numeric|min:0',
            'phosphorus' => 'nullable|numeric|min:0',
            'potassium' => 'nullable|numeric|min:0',
            'thingsboard_device_token' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'raw_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Find or create device
            $device = IotDevice::where('device_id', $request->device_id)->first();
            if (!$device) {
                $device = IotDevice::create([
                    'name' => 'IoT Device ' . $request->device_id,
                    'device_id' => $request->device_id,
                    'bluetooth_address' => $request->bluetooth_address ?? 'unknown',
                    'device_type' => 'soil_sensor',
                    'status' => 'online'
                ]);
            } else {
                $device->updateStatus('online');
            }

            // Store sensor data
            $sensorData = IotSensorData::create([
                'device_id' => $device->id,
                'kelas_id' => $request->kelas_id ?? 1, // Default class
                'user_id' => auth()->id(),
                'soil_temperature' => $request->soil_temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture,
                'ph_level' => $request->ph_level,
                'nitrogen' => $request->nitrogen,
                'phosphorus' => $request->phosphorus,
                'potassium' => $request->potassium,
                'thingsboard_device_token' => $request->thingsboard_device_token,
                'location' => $request->location,
                'notes' => $request->notes,
                'raw_data' => $request->raw_data,
                'measured_at' => now()
            ]);

            Log::info('IoT sensor data stored', [
                'device_id' => $device->id,
                'sensor_data_id' => $sensorData->id,
                'soil_temperature' => $request->soil_temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture,
                'nitrogen' => $request->nitrogen,
                'phosphorus' => $request->phosphorus,
                'potassium' => $request->potassium
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sensor data stored successfully',
                'data' => $sensorData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to store IoT sensor data', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to store sensor data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time sensor data
     */
    public function getRealTimeData(Request $request)
    {
        $deviceId = $request->device_id;
        
        $query = IotSensorData::with(['device', 'kelas', 'user']);
        
        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }
        
        $data = $query->latest('measured_at')->limit(50)->get();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get device status
     */
    public function getDeviceStatus(Request $request)
    {
        $devices = IotDevice::all();
        
        $status = $devices->map(function ($device) {
            return [
                'id' => $device->id,
                'device_name' => $device->name,
                'device_id' => $device->device_id,
                'status' => $device->status,
                'is_online' => $device->isOnline(),
                'last_seen' => $device->last_seen,
                'latest_data' => $device->latestSensorData
            ];
        });
        
        return response()->json([
            'success' => true,
            'devices' => $status
        ]);
    }

    /**
     * Create new research project
     */
    public function storeResearchProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'research_parameters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $project = ResearchProject::create([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'kelas_id' => $request->kelas_id,
                'teacher_id' => auth()->id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'research_parameters' => $request->research_parameters,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Research project created successfully',
                'project' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create research project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get research project data
     */
    public function getResearchProjectData(Request $request, $projectId)
    {
        $project = ResearchProject::with(['kelas', 'teacher'])
            ->where('id', $projectId)
            ->where('teacher_id', auth()->id())
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Research project not found'
            ], 404);
        }

        $sensorData = IotSensorData::where('kelas_id', $project->kelas_id)
            ->whereBetween('measured_at', [$project->start_date, $project->end_date ?? now()])
            ->with(['device', 'user'])
            ->orderBy('measured_at')
            ->get();

        return response()->json([
            'success' => true,
            'project' => $project,
            'sensor_data' => $sensorData
        ]);
    }

    /**
     * Display Admin IoT Dashboard
     */
    public function adminDashboard()
    {
        $devices = IotDevice::with('latestSensorData')->get();
        $recentData = IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        $kelas = Kelas::all();
        
        // Statistics
        $totalData = IotSensorData::count();
        $activeDevices = IotDevice::where('status', 'online')->count();
        $activeClasses = IotSensorData::distinct('kelas_id')->count();
        $activeProjects = ResearchProject::where('status', 'active')->count();
        
        return view('admin.iot-dashboard', compact('devices', 'recentData', 'kelas', 'totalData', 'activeDevices', 'activeClasses', 'activeProjects'))
            ->with('title', 'IoT Dashboard Admin');
    }

    /**
     * Display Super Admin IoT Dashboard
     */
    public function superAdminDashboard()
    {
        $devices = IotDevice::with('latestSensorData')->get();
        $recentData = IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        $kelas = Kelas::all();
        
        // Statistics
        $totalData = IotSensorData::count();
        $activeDevices = IotDevice::where('status', 'online')->count();
        $activeClasses = IotSensorData::distinct('kelas_id')->count();
        $activeProjects = ResearchProject::where('status', 'active')->count();
        
        return view('superadmin.iot-dashboard', compact('devices', 'recentData', 'kelas', 'totalData', 'activeDevices', 'activeClasses', 'activeProjects'))
            ->with('title', 'IoT Dashboard Super Admin');
    }

    /**
     * Sync data from ThingsBoard
     */
    public function syncFromThingsBoard()
    {
        try {
            $thingsBoardService = new ThingsBoardService();
            $result = $thingsBoardService->syncSensorData();

            return response()->json([
                'success' => true,
                'message' => 'Data synced successfully from ThingsBoard',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to sync from ThingsBoard', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync from ThingsBoard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ThingsBoard connection status
     */
    public function getThingsBoardStatus()
    {
        try {
            $thingsBoardService = new ThingsBoardService();
            $status = $thingsBoardService->checkConnection();

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check ThingsBoard status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
