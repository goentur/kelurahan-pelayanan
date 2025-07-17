<?php

use App\Http\Controllers\Transaksi\LaporanPenyampaianController;
use App\Http\Controllers\Transaksi\PenyampaianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('transaksi')->name('transaksi.')->group(function () {
    Route::prefix('penyampaian')->name('penyampaian.')->middleware('can:penyampaian-index')->controller(PenyampaianController::class)->group(function () {
        Route::get('form', 'index')->name('index');
        Route::post('data', 'data')->name('data');
        Route::post('tersampaikan', 'tersampaikan')->name('tersampaikan');
        Route::post('tidak-tersampaikan', 'tidakTersampaikan')->name('tidak-tersampaikan');
        Route::post('delete', 'delete')->name('delete');
        Route::prefix('lihat')->name('lihat.')->group(function () {
            Route::get('/', 'lihat')->name('index');
            Route::post('data', 'lihatData')->name('data');
        });
        Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
            Route::get('/', 'pengembalian')->name('index');
            Route::post('data', 'pengembalianData')->name('data');
            Route::get('cetak-ba/{id}', 'pengembalianCetakBA')->name('cetak-ba');
            Route::post('cetak-massal-ba', 'pengembalianCetakMassalBA')->name('cetak-massal-ba');
        });
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
