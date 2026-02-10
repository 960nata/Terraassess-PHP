<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ThingsBoardService;
use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\IotDeviceStatus;
use Illuminate\Support\Facades\Log;

class SyncDeviceFromThingsBoard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deviceId;
    protected $thingsBoardService;

    /**
     * Create a new job instance.
     */
    public function __construct($deviceId)
    {
        $this->deviceId = $deviceId;
        $this->thingsBoardService = app(ThingsBoardService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting sync job for device: ' . $this->deviceId);

            // Get latest data from ThingsBoard
            $thingsBoardData = $this->thingsBoardService->getDeviceLatestData($this->deviceId);
            
            if (!$thingsBoardData) {
                Log::warning('No data available from ThingsBoard for device: ' . $this->deviceId);
                return;
            }

            // Check if device exists in database
            $device = IotDevice::where('device_id', $this->deviceId)->first();
            
            if (!$device) {
                Log::info('Device not found in database, creating new device: ' . $this->deviceId);
                
                // Create device if not exists
                $device = IotDevice::create([
                    'name' => 'ESP8266 NPK Sensor',
                    'device_id' => $this->deviceId,
                    'device_type' => 'soil_sensor',
                    'status' => 'connected',
                    'user_id' => 1, // Default user
                    'device_info' => [
                        'platform' => 'thingsboard',
                        'token' => 'KddDFrocSfdHGumbd6Jz',
                        'server' => 'demo.thingsboard.io'
                    ],
                    'last_seen' => now()
                ]);
            } else {
                // Update device last_seen
                $device->update(['last_seen' => now()]);
            }

            // Save sensor data to database
            $sensorData = IotSensorData::create([
                'device_id' => $this->deviceId,
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
                ['device_id' => $this->deviceId],
                [
                    'is_online' => true,
                    'last_seen' => now(),
                    'system_info' => [
                        'last_sync' => now()->toISOString(),
                        'data_source' => 'thingsboard',
                        'sync_job' => 'success'
                    ]
                ]
            );

            Log::info('Sync job completed successfully for device: ' . $this->deviceId, [
                'sensor_data_id' => $sensorData->id,
                'temperature' => $thingsBoardData['temperature'] ?? 'N/A',
                'humidity' => $thingsBoardData['humidity'] ?? 'N/A',
                'ph' => $thingsBoardData['ph'] ?? 'N/A'
            ]);

        } catch (\Exception $e) {
            Log::error('Sync job failed for device: ' . $this->deviceId, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update device status to show error
            IotDeviceStatus::updateOrCreate(
                ['device_id' => $this->deviceId],
                [
                    'is_online' => false,
                    'last_seen' => now(),
                    'system_info' => [
                        'last_sync' => now()->toISOString(),
                        'data_source' => 'thingsboard',
                        'sync_job' => 'failed',
                        'error' => $e->getMessage()
                    ]
                ]
            );

            // Re-throw exception to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Sync job permanently failed for device: ' . $this->deviceId, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Update device status to show permanent failure
        IotDeviceStatus::updateOrCreate(
            ['device_id' => $this->deviceId],
            [
                'is_online' => false,
                'last_seen' => now(),
                'system_info' => [
                    'last_sync' => now()->toISOString(),
                    'data_source' => 'thingsboard',
                    'sync_job' => 'permanently_failed',
                    'error' => $exception->getMessage()
                ]
            ]
        );
    }
}


