<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('beranda', function () {
        return Inertia::render('beranda');
    })->name('beranda');
});

require __DIR__ . '/dashboard.php';
require __DIR__ . '/master.php';
require __DIR__ . '/pengaturan.php';
require __DIR__ . '/sppt.php';
require __DIR__ . '/transaksi.php';
require __DIR__ . '/user.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
