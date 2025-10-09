<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IotTugasController;

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
});