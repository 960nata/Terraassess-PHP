<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\IotDevice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SensorDataController extends Controller
{
    /**
     * Simpan data sensor dari ESP8266
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'device_id' => 'required|string',
                'temperature' => 'nullable|numeric',
                'humidity' => 'nullable|numeric',
                'ph' => 'nullable|numeric',
                'conductivity' => 'nullable|numeric',
                'nitrogen' => 'nullable|integer',
                'phosphorus' => 'nullable|integer',
                'potassium' => 'nullable|integer',
                'recorded_at' => 'nullable|date'
            ]);

            // Cek apakah device ada
            $device = IotDevice::where('device_id', $request->device_id)->first();
            if (!$device) {
                return response()->json(['error' => 'Device tidak ditemukan'], 404);
            }

            // Simpan data sensor
            $sensorData = SensorData::create([
                'device_id' => $request->device_id,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'ph' => $request->ph,
                'conductivity' => $request->conductivity,
                'nitrogen' => $request->nitrogen,
                'phosphorus' => $request->phosphorus,
                'potassium' => $request->potassium,
                'recorded_at' => $request->recorded_at ?? now()
            ]);

            Log::info('Data sensor berhasil disimpan', [
                'device_id' => $request->device_id,
                'sensor_data_id' => $sensorData->id,
                'temperature' => $sensorData->temperature,
                'humidity' => $sensorData->humidity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sensor berhasil disimpan',
                'data' => $sensorData
            ]);

        } catch (\Exception $e) {
            Log::error('Error menyimpan data sensor: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Gagal menyimpan data sensor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil data sensor dengan filter
     */
    public function index(Request $request)
    {
        try {
            $query = SensorData::query();

            // Filter berdasarkan device_id (WAJIB untuk keamanan)
            if ($request->has('device_id')) {
                $query->forDevice($request->device_id);
            } else {
                return response()->json([
                    'error' => 'Device ID diperlukan'
                ], 400);
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $sensorData = $query->orderBy('recorded_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $sensorData->items(),
                'pagination' => [
                    'total' => $sensorData->total(),
                    'per_page' => $sensorData->perPage(),
                    'current_page' => $sensorData->currentPage(),
                    'last_page' => $sensorData->lastPage(),
                    'from' => $sensorData->firstItem(),
                    'to' => $sensorData->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error mengambil data sensor: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Gagal mengambil data sensor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data sensor ke Excel
     */
    public function export(Request $request)
    {
        try {
            $query = SensorData::query();

            // Filter berdasarkan device_id (default ke device ESP8266 yang dimonitor)
            $deviceId = $request->get('device_id', '091334f0-a73e-11f0-8c95-7536037a85df');
            $query->forDevice($deviceId);

            // Filter berdasarkan tanggal (default semua data)
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            $sensorData = $query->orderBy('recorded_at', 'desc')->get();

            // Buat spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Sensor NPK');

            // Header dengan styling
            $headers = [
                'A1' => 'No',
                'B1' => 'Tanggal & Waktu',
                'C1' => 'Device ID',
                'D1' => 'Suhu (°C)',
                'E1' => 'Kelembaban (%)',
                'F1' => 'Tingkat pH',
                'G1' => 'Konduktivitas (µS/cm)',
                'H1' => 'Nitrogen (mg/kg)',
                'I1' => 'Fosfor (mg/kg)',
                'J1' => 'Kalium (mg/kg)'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Styling header
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2D3748']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ];
            $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

            // Data rows
            $row = 2;
            foreach ($sensorData as $index => $data) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $data->recorded_at->format('d/m/Y H:i:s'));
                $sheet->setCellValue('C' . $row, $data->device_id);
                $sheet->setCellValue('D' . $row, $data->temperature ?? '-');
                $sheet->setCellValue('E' . $row, $data->humidity ?? '-');
                $sheet->setCellValue('F' . $row, $data->ph ?? '-');
                $sheet->setCellValue('G' . $row, $data->conductivity ?? '-');
                $sheet->setCellValue('H' . $row, $data->nitrogen ?? '-');
                $sheet->setCellValue('I' . $row, $data->phosphorus ?? '-');
                $sheet->setCellValue('J' . $row, $data->potassium ?? '-');
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'J') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Buat file Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'data_sensor_npk_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filepath = storage_path('app/temp/' . $filename);
            
            // Pastikan direktori temp ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $writer->save($filepath);

            Log::info('Data sensor berhasil diekspor ke Excel', [
                'filename' => $filename,
                'record_count' => $sensorData->count(),
                'date_range' => $startDate . ' - ' . $endDate
            ]);

            return response()->download($filepath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error export data sensor ke Excel: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Gagal mengekspor data ke Excel',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}