<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\ResearchProject;
use App\Models\Kelas;
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
        
        return view('menu.pengajar.iot.dashboard', compact('devices', 'recentData'))
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
     * Show analytics dashboard
     */
    public function analytics()
    {
        return view('menu.pengajar.iot.analytics')
            ->with('title', 'IoT Analytics');
    }

    /**
     * Store sensor data from IoT device
     */
    public function storeSensorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'temperature' => 'required|numeric|between:-50,100',
            'humidity' => 'required|numeric|between:0,100',
            'soil_moisture' => 'required|numeric|between:0,100',
            'ph_level' => 'nullable|numeric|between:0,14',
            'nutrient_level' => 'nullable|numeric|min:0',
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
                    'device_name' => 'IoT Device ' . $request->device_id,
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
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture,
                'ph_level' => $request->ph_level,
                'nutrient_level' => $request->nutrient_level,
                'location' => $request->location,
                'notes' => $request->notes,
                'raw_data' => $request->raw_data,
                'measured_at' => now()
            ]);

            Log::info('IoT sensor data stored', [
                'device_id' => $device->id,
                'sensor_data_id' => $sensorData->id,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'soil_moisture' => $request->soil_moisture
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
                'device_name' => $device->device_name,
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
     * Get all IoT devices with statistics
     */
    public function getDevices()
    {
        try {
            $devices = IotDevice::with('kelas')->get();
            
            $statistics = [
                'total_devices' => $devices->count(),
                'connected_devices' => $devices->where('status', 'connected')->count(),
                'total_data_points' => $devices->sum('data_points'),
                'active_classes' => $devices->whereNotNull('class_id')->unique('class_id')->count()
            ];

            return response()->json([
                'success' => true,
                'devices' => $devices,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting devices: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data perangkat'
            ], 500);
        }
    }

    /**
     * Get analytics data for IoT dashboard
     */
    public function getAnalytics()
    {
        try {
            // Get data for last 30 days
            $startDate = now()->subDays(30);
            
            $analytics = [
                'temperature_avg' => IotSensorData::where('measured_at', '>=', $startDate)->avg('temperature'),
                'humidity_avg' => IotSensorData::where('measured_at', '>=', $startDate)->avg('humidity'),
                'soil_moisture_avg' => IotSensorData::where('measured_at', '>=', $startDate)->avg('soil_moisture'),
                'ph_level_avg' => IotSensorData::where('measured_at', '>=', $startDate)->avg('ph_level'),
                'nutrient_level_avg' => IotSensorData::where('measured_at', '>=', $startDate)->avg('nutrient_level'),
                'total_readings' => IotSensorData::where('measured_at', '>=', $startDate)->count(),
                'active_devices' => IotDevice::where('status', 'connected')->count(),
                'data_trends' => $this->getDataTrends($startDate)
            ];

            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting analytics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data analisis'
            ], 500);
        }
    }

    /**
     * Export IoT data to CSV/Excel
     */
    public function exportData(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $startDate = $request->get('start_date', now()->subDays(30));
            $endDate = $request->get('end_date', now());
            $deviceId = $request->get('device_id');

            $query = IotSensorData::with(['device', 'kelas', 'user'])
                ->whereBetween('measured_at', [$startDate, $endDate]);

            if ($deviceId) {
                $query->where('device_id', $deviceId);
            }

            $data = $query->orderBy('measured_at')->get();

            if ($format === 'excel') {
                return $this->exportToExcel($data);
            } else {
                return $this->exportToCsv($data);
            }
        } catch (\Exception $e) {
            Log::error('Error exporting data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data'
            ], 500);
        }
    }

    /**
     * Send IoT notifications
     */
    public function sendNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:alert,warning,info',
                'message' => 'required|string|max:500',
                'device_id' => 'nullable|string',
                'user_id' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Store notification in database
            $notification = \App\Models\Notification::create([
                'user_id' => $request->user_id ?? auth()->id(),
                'type' => $request->type,
                'title' => 'IoT Alert',
                'message' => $request->message,
                'data' => [
                    'device_id' => $request->device_id,
                    'timestamp' => now()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dikirim',
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim notifikasi'
            ], 500);
        }
    }

    /**
     * Get data trends for analytics
     */
    private function getDataTrends($startDate)
    {
        $trends = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = IotSensorData::whereDate('measured_at', $date)->get();
            
            $trends[] = [
                'date' => $date,
                'temperature_avg' => $dayData->avg('temperature'),
                'humidity_avg' => $dayData->avg('humidity'),
                'soil_moisture_avg' => $dayData->avg('soil_moisture'),
                'readings_count' => $dayData->count()
            ];
        }
        
        return $trends;
    }

    /**
     * Export data to CSV
     */
    private function exportToCsv($data)
    {
        $filename = 'iot_data_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Tanggal',
                'Perangkat',
                'Kelas',
                'Suhu (°C)',
                'Kelembaban (%)',
                'Kelembaban Tanah (%)',
                'pH Level',
                'Level Nutrisi (%)',
                'Lokasi',
                'Catatan'
            ]);

            // CSV data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->measured_at->format('Y-m-d H:i:s'),
                    $row->device->name ?? '-',
                    $row->kelas->name ?? '-',
                    $row->temperature,
                    $row->humidity,
                    $row->soil_moisture,
                    $row->ph_level,
                    $row->nutrient_level,
                    $row->location ?? '-',
                    $row->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data to Excel
     */
    private function exportToExcel($data)
    {
        // This would require Laravel Excel package
        // For now, return CSV format
        return $this->exportToCsv($data);
    }
}
