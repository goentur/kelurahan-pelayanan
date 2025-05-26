<?php

use App\Http\Controllers\Sppt\DataController;
use App\Http\Controllers\Sppt\Pelayanan\PerubahanAlamatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('sppt')->name('sppt.')->group(function () {
    Route::prefix('data')->name('data.')->group(function () {
        Route::middleware('can:sppt-data-index')->controller(DataController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
        });
    });
    Route::prefix('pelayanan')->name('pelayanan.')->group(function () {
        Route::prefix('perubahan-alamat')->name('perubahan-alamat.')->controller(PerubahanAlamatController::class)->group(function () {
            Route::middleware(['permission:pelayanan-perubahan-alamat-pemohon-index|pelayanan-perubahan-alamat-validasi-action'])->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'data')->name('data');
            });
            Route::middleware('can:pelayanan-perubahan-alamat-pemohon-create')->post('data-objek-pajak', 'dataObjekPajak')->name('data-objek-pajak');
            Route::middleware('can:pelayanan-perubahan-alamat-pemohon-update')->post('simpan-atau-update', 'simpanAtauUpdate')->name('simpan-atau-update');
            Route::middleware('can:pelayanan-perubahan-alamat-pemohon-delete')->post('hapus/{pelayananPerubahanAlamat}', 'hapus')->name('hapus');
            Route::middleware('can:pelayanan-perubahan-alamat-validasi-action')->post('terima/{pelayananPerubahanAlamat}', 'terima')->name('terima');
        });
    });
});
