<?php

use App\Http\Controllers\Settings\AplikasiController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('beranda', function () {
        return Inertia::render('beranda');
    })->name('beranda');
    Route::get('beranda/detail', function () {
        return Inertia::render('beranda');
    })->name('beranda.detail');
});

Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
    Route::prefix('aplikasi')->name('aplikasi.')->group(function () {
        Route::middleware('can:aplikasi-index')->group(function () {
            Route::get('/', [AplikasiController::class, 'index'])->name('index');
            Route::post('optimize-clear', [AplikasiController::class, 'optimizeClear'])->name('optimize-clear');
            Route::post('baku-awal', [AplikasiController::class, 'bakuAwal'])->name('baku-awal');
        });
        Route::middleware('can:aplikasi-update')->post('data', [AplikasiController::class, 'data'])->name('data');
    });
});

require __DIR__ . '/dashboard.php';
require __DIR__ . '/master.php';
require __DIR__ . '/sppt.php';
require __DIR__ . '/transaksi.php';
require __DIR__ . '/user.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
