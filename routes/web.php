<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KelolaAkunController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

require __DIR__ . '/auth.php';

// 2. Grup Rute yang WAJIB Login
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD MULTI-ROLE ---
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'anggota') {
            return view('dashboard_user');
        }
        return view('dashboard');
    })->name('dashboard');

    // --- PROFIL (Semua Role) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- FITUR SISWA / ANGGOTA (Katalog & Peminjaman Saya) ---
    Route::get('/katalog', [BukuController::class, 'index'])->name('katalog_buku.index');
    Route::get('/katalog/{id}', [BukuController::class, 'show'])->name('katalog.show');

    // Proses Simpan Pinjaman & Riwayat Pribadi
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('riwayat_peminjaman.index');


    // ------------------------------------------------------------------
    // 3. KHUSUS ADMIN & PETUGAS (Akses Manajemen)
    // ------------------------------------------------------------------
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->role === 'anggota') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
        }
        return $next($request);
    }], function () {

        // Manajemen Buku
        Route::resource('buku', BukuController::class);

        // Manajemen Peminjaman (Daftar semua pinjaman untuk divalidasi)
        // Kita beri nama route yang berbeda agar tidak bentrok dengan riwayat anggota
        Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::patch('/peminjaman/{id}/kembali', [PeminjamanController::class, 'update'])->name('peminjaman.update');

        // Pengembalian & Denda
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/proses/{id}', [PengembalianController::class, 'proses'])->name('pengembalian.proses');
        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');

        // Kelola Akun
        Route::resource('kelola-akun', KelolaAkunController::class)->names('kelola_akun');
       
        Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Berhasil mengajukan peminjaman!');
    });
});
