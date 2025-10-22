<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IotTugasController;
use App\Http\Controllers\IotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// IoT API Routes
Route::prefix('iot')->middleware('auth')->group(function () {
    Route::post('/readings', [IotTugasController::class, 'store'])->name('api.iot.store-reading');
    Route::get('/readings/class/{classId}', [IotTugasController::class, 'getClassReadings'])->name('api.iot.class-readings');
    Route::get('/readings/student/{studentId}', [IotTugasController::class, 'getStudentReadings'])->name('api.iot.student-readings');
    Route::get('/readings/export', [IotTugasController::class, 'exportCsv'])->name('api.iot.export-readings');
    Route::get('/readings/realtime', [IotTugasController::class, 'getRealTimeData'])->name('api.iot.readings-realtime');
    
    // IoT Sensor Data Routes
    Route::post('/sensor-data', [IotController::class, 'storeSensorData'])->name('api.iot.sensor-data');
    Route::get('/real-time-data', [IotController::class, 'getRealTimeData'])->name('api.iot.real-time-data');
    Route::get('/device-status', [IotController::class, 'getDeviceStatus'])->name('api.iot.device-status');
    Route::post('/research-project', [IotController::class, 'storeResearchProject'])->name('api.iot.research-project');
    Route::get('/research-project/{projectId}', [IotController::class, 'getResearchProjectData'])->name('api.iot.research-project-data');
    
    // ThingsBoard Integration Routes
    Route::get('/thingsboard/sync', [IotController::class, 'syncFromThingsBoard'])->name('api.iot.thingsboard-sync');
    Route::get('/thingsboard/status', [IotController::class, 'getThingsBoardStatus'])->name('api.iot.thingsboard-status');
});

// IoT Device Status API Routes (no authentication required for ESP8266)
Route::prefix('iot')->middleware([])->group(function () {
    Route::post('/device-status', [App\Http\Controllers\IotDeviceStatusController::class, 'updateStatus'])
        ->name('api.iot.device-status.update');
    Route::get('/device-status/{deviceId}', [App\Http\Controllers\IotDeviceStatusController::class, 'getStatus'])
        ->name('api.iot.device-status.get');
    Route::get('/devices-status', [App\Http\Controllers\IotDeviceStatusController::class, 'getAllDevicesStatus'])
        ->name('api.iot.devices-status.all');
    
    // Device Dashboard API Routes (no authentication required)
    Route::get('/device/{deviceId}/realtime', [App\Http\Controllers\IotDeviceController::class, 'getDeviceRealTimeData'])
        ->name('api.iot.device.realtime');
    Route::get('/device/{deviceId}/history', [App\Http\Controllers\IotDeviceController::class, 'getDeviceHistory'])
        ->name('api.iot.device.history');
    Route::post('/device/{deviceId}/sync', [App\Http\Controllers\IotDeviceController::class, 'syncDeviceFromThingsBoard'])
        ->name('api.iot.device.sync');
    Route::get('/device/{deviceId}/statistics', [App\Http\Controllers\IotDeviceController::class, 'getDeviceStatistics'])
        ->name('api.iot.device.statistics');
    
    // Device Status Update (for Arduino heartbeat)
    Route::post('/device-status', [App\Http\Controllers\IotDeviceController::class, 'updateDeviceStatus'])
        ->name('api.iot.device.status.update');
    
    // Sensor Data API Routes (no authentication required for ESP8266)
    Route::post('/sensor-data', [App\Http\Controllers\SensorDataController::class, 'store'])
        ->name('api.iot.sensor-data.store')
        ->withoutMiddleware([\App\Http\Middleware\HandleCsrfError::class]);
    Route::get('/sensor-data', [App\Http\Controllers\SensorDataController::class, 'index'])
        ->name('api.iot.sensor-data.index');
    Route::get('/sensor-data/export', [App\Http\Controllers\SensorDataController::class, 'export'])
        ->name('api.iot.sensor-data.export');
});