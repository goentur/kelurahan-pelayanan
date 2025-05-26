<?php

use App\Http\Controllers\Pendataan\SpopController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('pendataan')->name('pendataan.')->group(function () {
    Route::prefix('spop')->name('spop.')->group(function () {
        Route::middleware('can:pendataan-spop-index')->controller(SpopController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
            Route::post('cek-nop', 'cekNop')->name('cek-nop');
            Route::post('store', 'store')->name('store');
        });
    });
});
