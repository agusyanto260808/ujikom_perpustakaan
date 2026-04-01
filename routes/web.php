<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KelolaAkunController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik (Bisa diakses tanpa login)
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute Autentikasi (Login, Register, dll - biarkan di luar)
require __DIR__ . '/auth.php';

// 3. Grup Rute yang WAJIB Login
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Buku (Resource)
    Route::resource('buku', BukuController::class);



    Route::resource('peminjaman', PeminjamanController::class);
    // Untuk tombol pengembalian buku (karena pakai method PATCH)
    Route::patch('/peminjaman/{id}/kembali', [PeminjamanController::class, 'update'])->name('peminjaman.update');

    // Rute untuk Pengembalian Buku
    Route::get('/pengembalian', [App\Http\Controllers\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::patch('/pengembalian/{id}/kembali', [App\Http\Controllers\PengembalianController::class, 'store'])->name('pengembalian.store');

    // ... di dalam Route::middleware(['auth', 'verified'])->group ...

    Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');

    Route::resource('kelola-akun', KelolaAkunController::class)->names([
        'index'   => 'kelola_akun.index',
        'create'  => 'kelola_akun.create',
        'store'   => 'kelola_akun.store',
        'edit'    => 'kelola_akun.edit',
        'update'  => 'kelola_akun.update',
        'destroy' => 'kelola_akun.destroy',
    ]);
});
