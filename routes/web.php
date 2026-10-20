<?php

use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\SiswaPeminjamanController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// ---------- Publik ----------
Route::get('/', [HomeController::class, 'beranda'])->name('beranda');
Route::get('/alat', [HomeController::class, 'alat'])->name('alat.katalog');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

// ---------- Terautentikasi ----------
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Siswa
    Route::middleware([RoleMiddleware::class . ':siswa'])->prefix('peminjaman')->name('siswa.peminjaman.')->group(function () {
        Route::get('/', [SiswaPeminjamanController::class, 'index'])->name('index');
        Route::get('/ajukan', [SiswaPeminjamanController::class, 'create'])->name('create');
        Route::post('/', [SiswaPeminjamanController::class, 'store'])->name('store');
        Route::get('/{peminjaman}', [SiswaPeminjamanController::class, 'show'])->name('show');
    });

    // Admin
    Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('alat', AlatController::class);
        Route::resource('kategori', KategoriController::class)->except(['show', 'create']);
        Route::resource('users', UserController::class)->except(['show']);

        Route::get('peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('peminjaman/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('peminjaman/{peminjaman}/setujui', [AdminPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::post('peminjaman/{peminjaman}/tolak', [AdminPeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::post('peminjaman/{peminjaman}/serahkan', [AdminPeminjamanController::class, 'serahkan'])->name('peminjaman.serahkan');

        Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('pengembalian/{peminjaman}', [PengembalianController::class, 'form'])->name('pengembalian.form');
        Route::post('pengembalian/{peminjaman}', [PengembalianController::class, 'proses'])->name('pengembalian.proses');
    });

    // Laporan: admin dan guru
    Route::middleware([RoleMiddleware::class . ':admin,guru'])->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('/laporan/{peminjaman}', [LaporanController::class, 'show'])->name('laporan.show');
    });
});