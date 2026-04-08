<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

require __DIR__ . '/auth.php';

// 2. Grup Rute yang WAJIB Login
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD MULTI-ROLE ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- PROFIL (Semua Role) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- FITUR SISWA / ANGGOTA ---
    // --- FITUR SISWA / ANGGOTA ---
    Route::get('/katalog', [BukuController::class, 'index'])->name('katalog_buku.index');
    Route::get('/katalog/{id}', [BukuController::class, 'show'])->name('katalog.show');

    // Proses Simpan Pinjaman & Riwayat
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::patch('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('riwayat_peminjaman.index');


    Route::post('/riwayat-buku/ajukan/{id}', [PengembalianController::class, 'ajukan'])->name('pengembalian.ajukan');

    // ------------------------------------------------------------------



    Route::resource('buku', BukuController::class);

    // 1. Log Peminjaman Keseluruhan (Hanya List)
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');

    // 2. Halaman Konfirmasi Pengembalian (Ini yang memanggil $pengembalian)
    Route::get('/admin/konfirmasi-pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    // Route untuk memproses pengajuan dari user
    Route::post('/kembali-buku/ajukan/{id}', [PengembalianController::class, 'ajukan'])->name('pengembalian.ajukan');
    // Route ini untuk ADMIN menerima buku (store)
    Route::post('/pengembalian/store/{id}', [PengembalianController::class, 'store'])->name('pengembalian.store');

    Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
    Route::resource('kelola-akun', KelolaAkunController::class)->names('kelola_akun');
});
