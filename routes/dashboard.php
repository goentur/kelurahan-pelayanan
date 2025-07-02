<?php

use App\Http\Controllers\Dashboard\PenyampaianSPPTController;
use App\Http\Controllers\Dashboard\RealisasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::prefix('realisasi')->name('realisasi.')->group(function () {
        Route::middleware('can:dashboard-realisasi')->controller(RealisasiController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
            Route::post('data/per-kelurahan', 'dataPerKelurahan')->name('data-per-kelurahan');
        });
    });
    Route::prefix('penyampaian-sppt')->name('penyampaian-sppt.')->group(function () {
        Route::middleware('can:dashboard-penyampaian-sppt')->controller(PenyampaianSPPTController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('data', 'data')->name('data');
            Route::post('rekap-data', 'rekapData')->name('rekap-data');
        });
    });
});
