<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IotReading;
use App\Models\User;
use App\Models\Kelas;
use Carbon\Carbon;

class IotTugasController extends Controller
{
    /**
     * Display IoT tugas page
     */
    public function index()
    {
        $user = Auth::user();
        $kelas = Kelas::all();
        $recentReadings = IotReading::with(['student', 'kelas'])
            ->orderBy('timestamp', 'desc')
            ->paginate(20);
        
        return view('menu.pengajar.iot.tugas', compact('user', 'kelas', 'recentReadings'))
            ->with('title', 'IoT Tugas');
    }

    /**
     * Display hasil saya page
     */
    public function hasilSaya()
    {
        $user = Auth::user();
        $myReadings = IotReading::where('student_id', $user->id)
            ->orderBy('timestamp', 'desc')
            ->paginate(10);
        
        return view('menu.siswa.iot.hasil-saya', compact('user', 'myReadings'))
            ->with('title', 'Hasil Saya IoT');
    }

    /**
     * Store a new IoT reading
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'class_id' => 'required|integer',
            'soil_temperature' => 'required|numeric|min:0|max:100',
            'soil_humus' => 'required|numeric|min:0|max:100',
            'soil_moisture' => 'required|numeric|min:0|max:100',
            'device_id' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'raw_data' => 'nullable|array'
        ]);

        try {
            $reading = IotReading::create([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'soil_temperature' => $request->soil_temperature,
                'soil_humus' => $request->soil_humus,
                'soil_moisture' => $request->soil_moisture,
                'device_id' => $request->device_id,
                'location' => $request->location,
                'notes' => $request->notes,
                'raw_data' => $request->raw_data,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $reading
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get class readings
     */
    public function getClassReadings($classId)
    {
        try {
            $readings = IotReading::where('class_id', $classId)
                ->with(['student', 'kelas'])
                ->orderBy('timestamp', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $readings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student readings
     */
    public function getStudentReadings($studentId)
    {
        try {
            $readings = IotReading::where('student_id', $studentId)
                ->with(['kelas'])
                ->orderBy('timestamp', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $readings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export readings to CSV
     */
    public function exportCsv(Request $request)
    {
        try {
            $query = IotReading::query();

            if ($request->has('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->has('date_from')) {
                $query->whereDate('timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('timestamp', '<=', $request->date_to);
            }

            $readings = $query->with(['student', 'kelas'])->get();

            $filename = 'iot_readings_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($readings) {
                $file = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($file, [
                    'Timestamp',
                    'Student ID',
                    'Student Name',
                    'Class',
                    'Soil Temperature (°C)',
                    'Soil Humus (%)',
                    'Soil Moisture (%)',
                    'Device ID',
                    'Location',
                    'Notes'
                ]);

                // CSV data
                foreach ($readings as $reading) {
                    fputcsv($file, [
                        $reading->timestamp ? $reading->timestamp->format('Y-m-d H:i:s') : '',
                        $reading->student_id,
                        $reading->student->name ?? '',
                        $reading->kelas->name ?? '',
                        $reading->soil_temperature,
                        $reading->soil_humus,
                        $reading->soil_moisture,
                        $reading->device_id ?? '',
                        $reading->location ?? '',
                        $reading->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time data
     */
    public function getRealTimeData(Request $request)
    {
        try {
            $query = IotReading::query();

            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->has('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            // Get latest readings
            $readings = $query->orderBy('timestamp', 'desc')
                ->limit(10)
                ->get();

            // Calculate statistics
            $stats = [
                'total_readings' => $readings->count(),
                'avg_temperature' => $readings->avg('soil_temperature'),
                'avg_humus' => $readings->avg('soil_humus'),
                'avg_moisture' => $readings->avg('soil_moisture'),
                'latest_reading' => $readings->first(),
                'readings' => $readings
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data real-time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $query = IotReading::query();

            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->has('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            $totalReadings = $query->count();
            $todayReadings = $query->clone()->whereDate('timestamp', today())->count();
            $averageTemperature = $query->clone()->avg('soil_temperature') ?? 0;
            $averageMoisture = $query->clone()->avg('soil_moisture') ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_readings' => $totalReadings,
                    'today_readings' => $todayReadings,
                    'average_temperature' => round($averageTemperature, 1),
                    'average_moisture' => round($averageMoisture, 1)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}