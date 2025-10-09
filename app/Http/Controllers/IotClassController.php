<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\ResearchProject;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class IotClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Hanya guru (roles_id == 3) yang bisa akses
            if (Auth::user()->roles_id != 3) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Display IoT dashboard for teacher with class-based access
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Guru memiliki akses penuh ke semua kelas
        $assignedKelas = Kelas::with(['KelasMapel.Mapel'])->get();

        // Get all IoT devices
        $devices = IotDevice::with('latestSensorData')->get();

        // Get recent sensor data from all classes
        $recentData = IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();

        // Get statistics for all classes
        $totalDevices = $devices->count();
        $onlineDevices = $devices->where('status', 'online')->count();
        $totalReadings = IotSensorData::count();
        $todayReadings = IotSensorData::whereDate('measured_at', today())->count();

        return view('teacher.iot.dashboard', [
            'title' => 'IoT Dashboard - Guru',
            'user' => $user,
            'assignedKelas' => $assignedKelas,
            'devices' => $devices,
            'recentData' => $recentData,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'totalReadings' => $totalReadings,
            'todayReadings' => $todayReadings
        ]);
    }

    /**
     * Show IoT data for specific class
     */
    public function classData($kelasId)
    {
        $user = Auth::user();
        
        // Guru memiliki akses penuh ke semua kelas
        $kelas = Kelas::findOrFail($kelasId);

        // Get IoT devices for this specific class
        $devices = IotDevice::whereHas('sensorData', function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })->with('latestSensorData')->get();

        // Get sensor data for this class
        $sensorData = IotSensorData::where('kelas_id', $kelasId)
            ->with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->paginate(20);

        // Get research projects for this class
        $researchProjects = ResearchProject::where('kelas_id', $kelasId)
            ->with(['kelas', 'pengajar'])
            ->get();

        return view('teacher.iot.class-data', [
            'title' => 'Data IoT - ' . $kelasMapel->kelas->name,
            'user' => $user,
            'kelasMapel' => $kelasMapel,
            'devices' => $devices,
            'sensorData' => $sensorData,
            'researchProjects' => $researchProjects
        ]);
    }

    /**
     * Show IoT devices management for teacher
     */
    public function devices()
    {
        $user = Auth::user();
        
        // Get classes that this teacher teaches through EditorAccess
        $assignedKelas = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelasMapel')
            ->groupBy('kelas.id');

        // Get IoT devices assigned to teacher's classes
        $devices = IotDevice::whereHas('sensorData', function($query) use ($assignedKelas) {
            $kelasIds = $assignedKelas->keys()->toArray();
            $query->whereIn('kelas_id', $kelasIds);
        })->with(['latestSensorData', 'sensorData' => function($query) use ($assignedKelas) {
            $query->whereIn('kelas_id', $assignedKelas->keys()->toArray());
        }])->get();

        return view('teacher.iot.devices', [
            'title' => 'Manajemen Device IoT',
            'user' => $user,
            'assignedKelas' => $assignedKelas,
            'devices' => $devices
        ]);
    }

    /**
     * Show sensor data with filtering
     */
    public function sensorData(Request $request)
    {
        $user = Auth::user();
        
        // Get classes that this teacher teaches through EditorAccess
        $assignedKelas = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelasMapel')
            ->groupBy('kelas.id');

        $kelasIds = $assignedKelas->keys()->toArray();

        $query = IotSensorData::whereIn('kelas_id', $kelasIds)
            ->with(['device', 'kelas', 'user']);
        
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
        
        // Get devices and classes for filter dropdowns
        $devices = IotDevice::whereHas('sensorData', function($query) use ($kelasIds) {
            $query->whereIn('kelas_id', $kelasIds);
        })->get();
        
        $kelas = Kelas::whereIn('id', $kelasIds)->get();
        
        return view('teacher.iot.sensor-data', [
            'title' => 'Data Sensor IoT',
            'user' => $user,
            'assignedKelas' => $assignedKelas,
            'sensorData' => $sensorData,
            'devices' => $devices,
            'kelas' => $kelas
        ]);
    }

    /**
     * Show research projects for teacher's classes
     */
    public function researchProjects()
    {
        $user = Auth::user();
        
        // Get classes that this teacher teaches through EditorAccess
        $assignedKelas = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelasMapel')
            ->groupBy('kelas.id');

        $kelasIds = $assignedKelas->keys()->toArray();

        // Get research projects for teacher's classes
        $researchProjects = ResearchProject::whereIn('kelas_id', $kelasIds)
            ->with(['kelas', 'pengajar'])
            ->latest()
            ->get();

        return view('teacher.iot.research-projects', [
            'title' => 'Proyek Penelitian IoT',
            'user' => $user,
            'assignedKelas' => $assignedKelas,
            'researchProjects' => $researchProjects
        ]);
    }

    /**
     * Create new research project for specific class
     */
    public function createResearchProject(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'objectives' => 'required|string',
            'methodology' => 'required|string',
            'expected_outcomes' => 'required|string'
        ]);

        // Verify teacher has access to this class through EditorAccess
        $editorAccess = EditorAccess::where('user_id', $user->id)
            ->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            })
            ->first();
        
        $kelasMapel = $editorAccess?->kelasMapel;

        if (!$kelasMapel) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini'
            ], 403);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $researchProject = ResearchProject::create([
                'title' => $request->title,
                'description' => $request->description,
                'kelas_id' => $request->kelas_id,
                'teacher_id' => $user->id,
                'objectives' => $request->objectives,
                'methodology' => $request->methodology,
                'expected_outcomes' => $request->expected_outcomes,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek penelitian berhasil dibuat',
                'data' => $researchProject
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create research project', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat proyek penelitian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time sensor data for teacher's classes
     */
    public function getRealTimeData(Request $request)
    {
        $user = Auth::user();
        
        // Get classes that this teacher teaches through EditorAccess
        $assignedKelas = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelasMapel')
            ->groupBy('kelas.id');

        $kelasIds = $assignedKelas->keys()->toArray();
        
        $query = IotSensorData::whereIn('kelas_id', $kelasIds)
            ->with(['device', 'kelas', 'user']);
        
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }
        
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $data = $query->latest('measured_at')->limit(50)->get();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get device status for teacher's classes
     */
    public function getDeviceStatus(Request $request)
    {
        $user = Auth::user();
        
        // Get classes that this teacher teaches through EditorAccess
        $assignedKelas = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelasMapel')
            ->groupBy('kelas.id');

        $kelasIds = $assignedKelas->keys()->toArray();

        $devices = IotDevice::whereHas('sensorData', function($query) use ($kelasIds) {
            $query->whereIn('kelas_id', $kelasIds);
        })->get();
        
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
     * Store sensor data (API endpoint for IoT devices)
     */
    public function storeSensorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'temperature' => 'nullable|numeric',
            'humidity' => 'nullable|numeric',
            'soil_moisture' => 'nullable|numeric',
            'ph_level' => 'nullable|numeric',
            'nutrient_level' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find device by device_id
            $device = IotDevice::where('device_id', $request->device_id)->first();
            
            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device tidak ditemukan'
                ], 404);
            }

            // Store sensor data
            $sensorData = IotSensorData::create([
                'device_id' => $device->id,
                'kelas_id' => $request->kelas_id,
                'user_id' => auth()->id(),
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture,
                'ph_level' => $request->ph_level,
                'nutrient_level' => $request->nutrient_level,
                'measured_at' => now()
            ]);

            // Update device status
            $device->updateStatus('online');

            return response()->json([
                'success' => true,
                'message' => 'Data sensor berhasil disimpan',
                'data' => $sensorData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to store IoT sensor data', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data sensor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
