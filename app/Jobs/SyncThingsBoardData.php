<?php

namespace App\Jobs;

use App\Services\ThingsBoardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncThingsBoardData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting ThingsBoard sync job');

            $thingsBoardService = new ThingsBoardService();
            $result = $thingsBoardService->syncSensorData();

            Log::info('ThingsBoard sync job completed', [
                'synced_count' => $result['synced_count'],
                'error_count' => $result['error_count'],
                'total_devices' => $result['total_devices']
            ]);

        } catch (\Exception $e) {
            Log::error('ThingsBoard sync job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ThingsBoard sync job permanently failed', [
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
