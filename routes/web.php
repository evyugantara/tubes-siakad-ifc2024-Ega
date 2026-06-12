<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KrsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        
        Route::prefix('dosen')->name('dosen.')->group(function () {
            Route::get('/', [DosenController::class, 'index'])->name('index');
            Route::post('/', [DosenController::class, 'store'])->name('store');
            Route::put('/{nidn}', [DosenController::class, 'update'])->name('update');
            Route::delete('/{nidn}', [DosenController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
            Route::get('/', [MahasiswaController::class, 'index'])->name('index');
            Route::post('/', [MahasiswaController::class, 'store'])->name('store');
            Route::put('/{npm}', [MahasiswaController::class, 'update'])->name('update');
            Route::delete('/{npm}', [MahasiswaController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('matakuliah')->name('matakuliah.')->group(function () {
            Route::get('/', [MataKuliahController::class, 'index'])->name('index');
            Route::post('/', [MataKuliahController::class, 'store'])->name('store');
            Route::put('/{kode}', [MataKuliahController::class, 'update'])->name('update');
            Route::delete('/{kode}', [MataKuliahController::class, 'destroy'])->name('destroy');
        });

        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    });

    Route::middleware('role:admin,mahasiswa')->group(function () {
        
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

        Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
        Route::delete('/krs/{id}', [KrsController::class, 'destroy'])->name('krs.destroy');
    });

    Route::middleware('role:mahasiswa')->group(function () {
        
        Route::post('/krs', [KrsController::class, 'store'])->name('krs.store');
        
        Route::get('/krs/cetak', [KrsController::class, 'print'])->name('krs.print');

        Route::get('/krs/export', [KrsController::class, 'exportCsv'])->name('krs.export');
    });
});
