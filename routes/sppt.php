<?php

use App\Http\Controllers\Sppt\DataController;
use Illuminate\Support\Facades\Route;

Route::prefix('sppt')->name('sppt.')->group(function () {
    Route::prefix('data')->name('data.')->group(function () {
        Route::middleware('can:sppt-data-index')->controller(DataController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'data')->name('data');
        });
    });
});
