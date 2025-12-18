<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\DeviceManager\Http\Controllers\DeviceManagerController;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('devices', DeviceManagerController::class)->names('devices');
    });

