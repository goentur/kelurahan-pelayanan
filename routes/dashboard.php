<?php

use App\Http\Controllers\Dashboard\RealisasiController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::prefix('realisasi')->name('realisasi.')->group(function () {
        Route::middleware('can:dashboard-realisasi')->controller(RealisasiController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
        });
    });
});
