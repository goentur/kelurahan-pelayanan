<?php

use App\Http\Controllers\Transaksi\LaporanPenyampaianController;
use App\Http\Controllers\Transaksi\PenyampaianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('transaksi')->name('transaksi.')->group(function () {
    Route::prefix('penyampaian')->name('penyampaian.')->middleware('can:penyampaian-index')->controller(PenyampaianController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('data', 'data')->name('data');
        Route::post('store', 'store')->name('store');
        Route::post('delete', 'delete')->name('delete');
    });
    Route::prefix('laporan-penyampaian')->name('laporan-penyampaian.')->middleware('can:laporan-penyampaian-index')->controller(LaporanPenyampaianController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{jenisLapor}', 'form')->name('form');
        Route::post('data', 'data')->name('data');
        Route::post('simpan', 'simpan')->name('simpan');
        Route::post('berdasarkan-kelurahan', 'berdasarkanKelurahan')->name('berdasarkan-kelurahan');
        Route::post('gabungan/{jenisLapor}', 'gabungan')->name('gabungan');
    });
});
