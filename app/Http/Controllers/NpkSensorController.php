<?php

namespace App\Http\Controllers;

use App\Services\ThingsBoardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class NpkSensorController extends Controller
{
    protected $thingsBoardService;

    public function __construct(ThingsBoardService $thingsBoardService)
    {
        $this->thingsBoardService = $thingsBoardService;
    }

    /**
     * Tampilkan dashboard sensor NPK dengan data dari ThingsBoard
     */
    public function showDashboard()
    {
        try {
            // Test koneksi ThingsBoard terlebih dahulu
            if (!$this->thingsBoardService->testConnection()) {
                return $this->handleError('Tidak dapat terhubung ke ThingsBoard. Periksa kredensial dan koneksi internet.');
            }

            // Ambil data telemetri terakhir
            $sensorData = $this->thingsBoardService->getLatestTelemetry();
            
            // Ambil info device untuk konteks tambahan
            $deviceInfo = $this->thingsBoardService->getDeviceInfo();
            
            // Format data untuk view
            $formattedData = [
                'sensor_data' => $sensorData,
                'device_info' => $deviceInfo,
                'last_updated' => now()->format('Y-m-d H:i:s'),
                'status' => 'connected'
            ];

            Log::info('Sensor dashboard data fetched successfully', [
                'device_id' => env('THINGSBOARD_DEVICE_ID'),
                'data_count' => count($sensorData)
            ]);

            return view('sensor.dashboard', ['data' => $formattedData]);

        } catch (Exception $e) {
            Log::error('Error in sensor dashboard: ' . $e->getMessage());
            return $this->handleError('Terjadi kesalahan saat mengambil data sensor: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk mendapatkan data sensor (untuk AJAX calls)
     */
    public function getSensorData()
    {
        try {
            $sensorData = $this->thingsBoardService->getLatestTelemetry();
            
            return response()->json([
                'success' => true,
                'data' => $sensorData,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Error in getSensorData API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Test koneksi ThingsBoard
     */
    public function testConnection()
    {
        try {
            $isConnected = $this->thingsBoardService->testConnection();
            
            return response()->json([
                'success' => $isConnected,
                'message' => $isConnected ? 'Koneksi ThingsBoard berhasil' : 'Koneksi ThingsBoard gagal',
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get device information
     */
    public function getDeviceInfo()
    {
        try {
            $deviceInfo = $this->thingsBoardService->getDeviceInfo();
            
            return response()->json([
                'success' => true,
                'device_info' => $deviceInfo,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Error in getDeviceInfo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Handle error dengan fallback data
     */
    private function handleError($message)
    {
        // Fallback data jika ThingsBoard tidak dapat diakses
        $fallbackData = [
            'sensor_data' => [
                'Suhu' => null,
                'Kelembaban' => null,
                'Konduktivitas' => null,
                'pH' => null,
                'Nitrogen' => null,
                'Fosfor' => null,
                'Kalium' => null
            ],
            'device_info' => [
                'name' => 'NPK Sensor Device',
                'type' => 'NPK Sensor',
                'label' => 'Sensor NPK Tanah'
            ],
            'last_updated' => now()->format('Y-m-d H:i:s'),
            'status' => 'error',
            'error_message' => $message
        ];

        return view('sensor.dashboard', ['data' => $fallbackData]);
    }

    /**
     * Refresh data sensor (untuk AJAX refresh)
     */
    public function refreshData()
    {
        try {
            // Clear session token untuk force re-login
            $this->thingsBoardService->logout();
            
            // Ambil data fresh
            $sensorData = $this->thingsBoardService->getLatestTelemetry();
            
            return response()->json([
                'success' => true,
                'data' => $sensorData,
                'message' => 'Data berhasil di-refresh',
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Error in refreshData: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Show public test page (no authentication required)
     */
    public function showPublicTest()
    {
        try {
            // Test connection first
            if (!$this->thingsBoardService->testConnection()) {
                return view('sensor.test', [
                    'error' => 'Tidak dapat terhubung ke ThingsBoard. Pastikan konfigurasi sudah benar.'
                ]);
            }

            // Get sensor data
            $sensorData = $this->thingsBoardService->getLatestTelemetry();
            
            $formattedData = [
                'sensor_data' => $sensorData,
                'last_updated' => now()->format('Y-m-d H:i:s'),
                'status' => 'connected'
            ];

            return view('sensor.test', ['data' => $formattedData]);
        } catch (Exception $e) {
            return view('sensor.test', [
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get sensor data for public API (no authentication required)
     */
    public function getPublicSensorData()
    {
        $maxRetries = 3;
        $retryDelay = 1000; // milliseconds
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                Log::info("Attempting to connect to ThingsBoard (attempt {$attempt}/{$maxRetries})");
                
                if ($this->thingsBoardService->testConnection()) {
                    $sensorData = $this->thingsBoardService->getLatestTelemetry();
                    
                    $formattedData = [
                        'sensor_data' => $sensorData,
                        'last_updated' => now()->format('Y-m-d H:i:s'),
                        'status' => 'connected'
                    ];

                    return response()->json([
                        'success' => true,
                        'is_simulated' => false,
                        'data' => $formattedData
                    ]);
                }
                
                if ($attempt < $maxRetries) {
                    Log::warning("ThingsBoard connection failed, retrying in {$retryDelay}ms...");
                    usleep($retryDelay * 1000); // Convert to microseconds
                }
                
            } catch (Exception $e) {
                Log::error("ThingsBoard connection attempt {$attempt} failed: " . $e->getMessage());
                
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * 1000);
                }
            }
        }
        
        // All retries failed, return fallback data
        Log::warning('All ThingsBoard connection attempts failed, returning simulated data');
        
        $fallbackData = $this->thingsBoardService->getFallbackData();
        $formattedData = [
            'sensor_data' => $fallbackData,
            'last_updated' => now()->format('Y-m-d H:i:s'),
            'status' => 'simulated'
        ];

        return response()->json([
            'success' => true,
            'is_simulated' => true,
            'data' => $formattedData,
            'message' => 'Using simulated data - ThingsBoard offline'
        ]);
    }
}
