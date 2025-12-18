<?php

use Illuminate\Support\Facades\Route;
use Modules\DeviceManager\Http\Controllers\DeviceManagerController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('devicemanagers', DeviceManagerController::class)->names('devicemanager');
});

