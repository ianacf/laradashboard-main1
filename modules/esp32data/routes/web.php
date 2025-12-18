<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Esp32data\Http\Controllers\Esp32dataController;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('esp32s', Esp32dataController::class)->names('esp32s');
    });