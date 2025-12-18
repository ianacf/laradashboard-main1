<?php

use Illuminate\Support\Facades\Route;
use Modules\Esp32data\Http\Controllers\Esp32dataController;
use Modules\Esp32data\Http\Controllers\Api\Esp32InputController;
use Modules\Esp32data\Http\Controllers\Api\Esp32ChartController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('esp32datas', Esp32dataController::class)->names('esp32data');
});

// Public endpoints for ESP32 devices (no authentication required)
Route::prefix('v1')->group(function () {
    // Health check
    Route::get('esp32datas/health', [Esp32InputController::class, 'healthCheck'])
        ->name('esp32data.health');
    
    // Data input endpoints
    Route::get('esp32datas/input', [Esp32InputController::class, 'inputData'])
        ->name('esp32data.input.get');
    Route::post('esp32datas/input', [Esp32InputController::class, 'postData'])
        ->name('esp32data.input.post');
    Route::post('esp32datas/input/bulk', [Esp32InputController::class, 'inputBulk'])
        ->name('esp32data.input.bulk');

    // Chart data (last 50 points)
    Route::get('esp32datas/chart/latest', [Esp32ChartController::class, 'latest'])
        ->name('esp32data.chart.latest');
});

