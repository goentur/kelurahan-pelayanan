<?php

use App\Http\Controllers\Cetak\PenyampaianSPPTController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cetak')->name('cetak.')->group(function () {
    Route::prefix('penyampaian-sppt')->name('penyampaian-sppt.')->group(function () {
        Route::middleware('can:cetak-penyampaian-sppt')->controller(PenyampaianSPPTController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('tidak-tersampaikan', 'tidakTersampaikan')->name('tidak-tersampaikan');
            Route::post('tersampaikan', 'tersampaikan')->name('tersampaikan');
        });
    });
});
