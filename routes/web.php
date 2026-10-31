<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PendudukLahirController;
use App\Http\Controllers\PendudukMeninggalController;
use App\Http\Controllers\PendudukPendatangController;
use App\Http\Controllers\PendudukPindahController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Users Management
    Route::resource('users', AdminUserController::class)->except(['show']);

    // Data Master Desa / Kelurahan
    Route::resource('desa', DesaController::class)->except(['show']);

    // Data Master Penduduk Tetap
    Route::resource('penduduk', PendudukController::class);

    // Data Mutasi Kependudukan
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        // Penduduk Pindah
        Route::resource('pindah', PendudukPindahController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

        // Penduduk Meninggal
        Route::resource('meninggal', PendudukMeninggalController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

        // Penduduk Lahir
        Route::resource('lahir', PendudukLahirController::class);

        // Penduduk Pendatang
        Route::resource('pendatang', PendudukPendatangController::class);
        Route::post('pendatang/{pendatang}/jadikan-tetap', [PendudukPendatangController::class, 'jadikanTetap'])
            ->name('pendatang.jadikan-tetap');
    });

    // Modul Laporan & Cetak PDF
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
});
