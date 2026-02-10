<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;

class ThingsBoardService
{
    private $serverUrl;
    private $accessToken;
    private $deviceId;

    public function __construct()
    {
        $this->serverUrl = config('thingsboard.server');
        $this->accessToken = config('thingsboard.access_token');
        $this->deviceId = config('thingsboard.device_id');
    }

    /**
     * Get fallback data for simulation when ThingsBoard is offline
     */
    public function getFallbackData()
    {
        return [
            'temperature' => 28.5 + (rand(-20, 20) / 10), // 26.5 - 30.5
            'humidity' => 65.0 + (rand(-100, 100) / 10), // 55.0 - 75.0
            'conductivity' => 1.2 + (rand(-20, 20) / 100), // 1.0 - 1.4
            'ph' => 6.8 + (rand(-20, 20) / 100), // 6.6 - 7.0
            'nitrogen' => 45 + rand(-10, 10), // 35 - 55
            'phosphorus' => 30 + rand(-8, 8), // 22 - 38
            'potassium' => 35 + rand(-10, 10) // 25 - 45
        ];
    }

    /**
     * Ambil data telemetri terakhir dari device menggunakan access token
     */
    public function getLatestTelemetry()
    {
        try {
            if (!$this->accessToken) {
                throw new Exception('ThingsBoard access token not configured');
            }

            $endpoint = str_replace('{accessToken}', $this->accessToken, config('thingsboard.endpoints.telemetry'));
            $url = $this->serverUrl . $endpoint . '?keys=Suhu,Kelembaban,pH,Nitrogen,Fosfor,Kalium';
            
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatTelemetryData($data);
            } else {
                Log::error('Failed to get telemetry data: ' . $response->body());
                throw new Exception('Failed to fetch telemetry data from ThingsBoard');
            }
        } catch (Exception $e) {
            Log::error('Telemetry fetch exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format data telemetri dari ThingsBoard ke array yang rapi
     */
    private function formatTelemetryData($rawData)
    {
        $formattedData = [];
        
        // Mapping key ThingsBoard ke key frontend (lowercase English)
        $keyMapping = [
            'Suhu' => 'temperature',
            'Kelembaban' => 'humidity', 
            'Konduktivitas' => 'conductivity',
            'pH' => 'ph',
            'nitrogen' => 'nitrogen',
            'Fosfor' => 'phosphorus',
            'Kalium' => 'potassium',
            // Alternative keys from ThingsBoard
            'temperature' => 'temperature',
            'humidity' => 'humidity',
            'conductivity' => 'conductivity',
            'ph' => 'ph',
            'phosphorus' => 'phosphorus',
            'potassium' => 'potassium'
        ];

        foreach ($keyMapping as $tbKey => $frontendKey) {
            if (isset($rawData[$tbKey])) {
                if (is_array($rawData[$tbKey]) && count($rawData[$tbKey]) > 0) {
                    // Ambil nilai terakhir (index 0 adalah yang terbaru)
                    $latestValue = $rawData[$tbKey][0];
                    $formattedData[$frontendKey] = $latestValue['value'];
                } else {
                    // Direct value (not array)
                    $formattedData[$frontendKey] = $rawData[$tbKey];
                }
            }
        }

        return $formattedData;
    }

    /**
     * Test koneksi ke ThingsBoard dengan timeout 5 detik
     */
    public function testConnection()
    {
        try {
            if (!$this->accessToken) {
                return false;
            }

            $endpoint = str_replace('{accessToken}', $this->accessToken, config('thingsboard.endpoints.telemetry'));
            $url = $this->serverUrl . $endpoint . '?keys=Suhu';
            
            $response = Http::timeout(5)->get($url);
            return $response->successful();
        } catch (Exception $e) {
            Log::error('ThingsBoard connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get device telemetry data for specific device
     */
    public function getDeviceTelemetry($deviceId, $keys = null)
    {
        try {
            $token = $this->getValidToken();
            
            $url = $this->serverUrl . '/api/plugins/telemetry/DEVICE/' . $deviceId . '/values/timeseries';
            
            if ($keys) {
                $url .= '?keys=' . implode(',', $keys);
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatTelemetryData($data);
            } else {
                Log::error('Failed to get device telemetry: ' . $response->body());
                throw new Exception('Failed to fetch device telemetry from ThingsBoard');
            }
        } catch (Exception $e) {
            Log::error('Device telemetry fetch exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get latest data for specific device
     */
    public function getDeviceLatestData($deviceId)
    {
        try {
            $token = $this->getValidToken();
            
            // Get latest telemetry data
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($this->serverUrl . '/api/plugins/telemetry/DEVICE/' . $deviceId . '/values/timeseries');

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatLatestTelemetryData($data);
            } else {
                Log::error('Failed to get latest device data: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Latest device data fetch exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get device attributes
     */
    public function getDeviceAttributes($deviceId)
    {
        try {
            $token = $this->getValidToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($this->serverUrl . '/api/plugins/telemetry/DEVICE/' . $deviceId . '/values/attributes');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to get device attributes: ' . $response->body());
                return null;
            }
        } catch (Exception $e) {
            Log::error('Device attributes fetch exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get device info (simplified for token-based auth)
     */
    public function getDeviceInfo($deviceId = null)
    {
        // Return basic device info for token-based auth
        return [
            'name' => 'NPK Sensor Device',
            'type' => 'NPK Sensor',
            'label' => 'Sensor NPK Tanah',
            'deviceId' => $this->deviceId
        ];
    }

    /**
     * Format latest telemetry data (single values)
     */
    private function formatLatestTelemetryData($rawData)
    {
        $formattedData = [];
        
        // Mapping key ThingsBoard ke key yang diinginkan
        $keyMapping = [
            'temperature' => 'temperature',
            'humidity' => 'humidity', 
            'conductivity' => 'conductivity',
            'ph' => 'ph',
            'nitrogen' => 'nitrogen',
            'phosphorus' => 'phosphorus',
            'potassium' => 'potassium',
            'Suhu' => 'temperature',
            'Kelembaban' => 'humidity', 
            'Konduktivitas' => 'conductivity',
            'pH' => 'ph',
            'nitrogen' => 'nitrogen',
            'Fosfor' => 'phosphorus',
            'Kalium' => 'potassium'
        ];

        foreach ($keyMapping as $tbKey => $outputKey) {
            if (isset($rawData[$tbKey]) && is_array($rawData[$tbKey]) && count($rawData[$tbKey]) > 0) {
                // Ambil nilai terakhir (index 0 adalah yang terbaru)
                $latestValue = $rawData[$tbKey][0];
                $formattedData[$outputKey] = $latestValue['value'];
            }
        }

        return $formattedData;
    }

    /**
     * Sync data from ThingsBoard to database
     */
    public function syncDeviceData($deviceId)
    {
        try {
            $latestData = $this->getDeviceLatestData($deviceId);
            
            if (!$latestData) {
                return null;
            }

            // Save to database
            $sensorData = \App\Models\IotSensorData::create([
                'device_id' => $deviceId,
                'soil_temperature' => $latestData['temperature'] ?? null,
                'soil_moisture' => $latestData['humidity'] ?? null,
                'soil_conductivity' => $latestData['conductivity'] ?? null,
                'soil_ph' => $latestData['ph'] ?? null,
                'nitrogen' => $latestData['nitrogen'] ?? null,
                'phosphorus' => $latestData['phosphorus'] ?? null,
                'potassium' => $latestData['potassium'] ?? null,
                'measured_at' => now()
            ]);

            return $sensorData;

        } catch (Exception $e) {
            Log::error('Error syncing device data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear session token (untuk logout)
     */
    public function logout()
    {
        Session::forget(['thingsboard_token', 'thingsboard_token_expires']);
        $this->token = null;
    }
}