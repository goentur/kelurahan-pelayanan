<?php

use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\JenisLaporController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\PenyampaianKeteranganController;
use App\Http\Controllers\Master\SatuanKerjaController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->name('master.')->group(function () {
    Route::prefix('satuan-kerja')->name('satuan-kerja.')->group(function () {
        Route::middleware('can:satuan-kerja-index')->post('data', [SatuanKerjaController::class, 'data'])->name('data');
        Route::post('list', [SatuanKerjaController::class, 'list'])->name('list');
        Route::post('data-berdasarkan-user', [SatuanKerjaController::class, 'dataBerdasarkanUser'])->name('data-berdasarkan-user');
    });
    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::middleware('can:jabatan-index')->post('data', [JabatanController::class, 'data'])->name('data');
        Route::middleware('can:jabatan-create')->post('data-jenis', [JabatanController::class, 'dataJenis'])->name('data-jenis');
        Route::post('list', [JabatanController::class, 'list'])->name('list');
    });
    Route::prefix('pegawai')->name('pegawai.')->middleware('can:pegawai-index')->group(function () {
        Route::post('data', [PegawaiController::class, 'data'])->name('data');
        Route::post('status', [PegawaiController::class, 'status'])->name('status');
    });
    Route::prefix('penyampaian-keterangan')->name('penyampaian-keterangan.')->group(function () {
        Route::middleware('can:penyampaian-keterangan-index')->post('data', [PenyampaianKeteranganController::class, 'data'])->name('data');
        Route::post('list', [PenyampaianKeteranganController::class, 'list'])->name('list');
    });
    Route::prefix('jenis-lapor')->name('jenis-lapor.')->group(function () {
        Route::middleware('can:jenis-lapor-index')->post('data', [JenisLaporController::class, 'data'])->name('data');
        Route::post('list', [JenisLaporController::class, 'list'])->name('list');
    });
    Route::prefix('jenis-lapor')->name('jenis-lapor.')->group(function () {
        Route::middleware('can:jenis-lapor-index')->post('data', [JenisLaporController::class, 'data'])->name('data');
        Route::post('list', [JenisLaporController::class, 'list'])->name('list');
    });
    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::middleware('can:pengguna-index')->post('data', [PenggunaController::class, 'data'])->name('data');
    });
    Route::resource('satuan-kerja', SatuanKerjaController::class)->middleware('can:satuan-kerja-index');
    Route::resource('jabatan', JabatanController::class)->middleware('can:jabatan-index');
    Route::resource('penyampaian-keterangan', PenyampaianKeteranganController::class)->middleware('can:penyampaian-keterangan-index');
    Route::resource('pegawai', PegawaiController::class)->middleware('can:pegawai-index');
    Route::resource('jenis-lapor', JenisLaporController::class)->middleware('can:jenis-lapor-index');
    Route::resource('pengguna', PenggunaController::class)->middleware('can:pengguna-index');
});
