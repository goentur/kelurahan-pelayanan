<?php

use App\Http\Controllers\Pendataan\SpopController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('pendataan')->name('pendataan.')->group(function () {
    Route::prefix('spop')->name('spop.')->group(function () {
        Route::middleware('can:pendataan-spop-index')->controller(SpopController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
            Route::post('cek-nop', 'cekNop')->name('cek-nop');
            Route::post('nop-terbesar', 'nopTerbesar')->name('nop-terbesar');
            Route::post('store', 'store')->name('store');
            Route::patch('update', 'update')->name('update');
            Route::delete('delete/{pendataanSpop}', 'destroy')->name('destroy');
            Route::post('data-detail', 'dataDetail')->name('data-detail');
            Route::post('add-bangunan', 'addBangunan')->name('add-bangunan');
            Route::post('update-bangunan', 'updateBangunan')->name('update-bangunan');
            Route::delete('delete-bangunan', 'deleteBangunan')->name('delete-bangunan');
            Route::get('unduh-hasil-pendataan-per-user', 'unduhHasilPendataanPerUser')->name('unduh-hasil-pendataan-per-user');
        });
    });
});
