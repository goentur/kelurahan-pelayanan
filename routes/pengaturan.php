<?php

use App\Http\Controllers\Pengaturan\AplikasiController;
use App\Http\Controllers\Pengaturan\BakuAwalController;
use App\Http\Controllers\Pengaturan\PenyampaianSpptController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('pengaturan')->name('pengaturan.')->group(function () {
    Route::middleware('can:pengaturan-baku-awal')->prefix('baku-awal')->name('baku-awal.')->controller(BakuAwalController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store-or-update', 'storeOrUpdate')->name('store-or-update');
    });
    Route::middleware('can:aplikasi-index')->prefix('aplikasi')->name('aplikasi.')->controller(AplikasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('optimize-clear', 'optimizeClear')->name('optimize-clear');
    });
    Route::middleware('can:pengaturan-penyampaian-sppt')->prefix('penyampaian-sppt')->name('penyampaian-sppt.')->controller(PenyampaianSpptController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('reset-data-laporan-penyampaian-sppt', 'resetDataLaporanPenyampaianSppt')->name('reset-data-laporan-penyampaian-sppt');
    });
});
Route::get('aplikasi/log-viewer', null)->name('log-viewer');
