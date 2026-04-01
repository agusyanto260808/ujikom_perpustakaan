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

    // Manajemen Profil (Bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ------------------------------------------------------------------
    // 3. KHUSUS ADMIN (Kepala Perpus & Petugas)
    // ------------------------------------------------------------------
    // Menggunakan Route::group agar bisa memasukkan logika pengecekan role langsung
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->role === 'anggota') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses admin.');
        }
        return $next($request);
    }], function () {

        // Dashboard Admin
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // Manajemen Buku, Peminjaman, Akun
        Route::resource('buku', BukuController::class);
        Route::resource('peminjaman', PeminjamanController::class);
        Route::patch('/peminjaman/{id}/kembali', [PeminjamanController::class, 'update'])->name('peminjaman.update');

        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/proses/{id}', [PengembalianController::class, 'proses'])->name('pengembalian.proses');

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

    // ------------------------------------------------------------------
    // 4. KHUSUS ANGGOTA
    // ------------------------------------------------------------------
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->role !== 'anggota') {
            // Opsional: Jika admin mau akses halaman anggota juga, biarkan $next
            return $next($request);
        }
        return $next($request);
    }], function () {
        // Tambahkan route khusus anggota di sini, misal:
        // Route::get('/buku-tamu', [BukuController::class, 'list'])->name('buku.user');
    });
});
