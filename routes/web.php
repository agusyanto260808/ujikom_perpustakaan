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

    // --- FITUR SISWA / ANGGOTA ---
    // --- FITUR SISWA / ANGGOTA ---
    Route::get('/katalog', [BukuController::class, 'index'])->name('katalog_buku.index');
    Route::get('/katalog/{id}', [BukuController::class, 'show'])->name('katalog.show');

    // Proses Simpan Pinjaman & Riwayat
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::patch('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('riwayat_peminjaman.index');

    // --- PINDAHKAN KE SINI (Agar Anggota Bisa Akses) ---
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('riwayat_peminjaman.index');
    Route::post('/riwayat-buku/ajukan/{id}', [PengembalianController::class, 'ajukan'])->name('pengembalian.ajukan');

    // ------------------------------------------------------------------
    // 3. KHUSUS ADMIN & PETUGAS (Akses Manajemen)
    // ------------------------------------------------------------------
    // --- KHUSUS ADMIN & PETUGAS ---
    Route::middleware(['auth'])->group(function () {
        Route::group(['middleware' => function ($request, $next) {
            if (auth()->user()->role === 'anggota') {
                return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
            }
            return $next($request);
        }], function () {

            Route::resource('buku', BukuController::class);

            // 1. Log Peminjaman Keseluruhan (Hanya List)
            Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');

            // 2. Halaman Konfirmasi Pengembalian (Ini yang memanggil $pengembalian)
            Route::get('/admin/konfirmasi-pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
            // Route untuk memproses pengajuan dari user
            Route::post('/kembali-buku/ajukan/{id}', [PengembalianController::class, 'ajukan'])->name('pengembalian.ajukan');
            Route::post('/pengembalian/store/{id}', [PengembalianController::class, 'store'])->name('pengembalian.store');

            Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
            Route::resource('kelola-akun', KelolaAkunController::class)->names('kelola_akun');
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->middleware(['auth'])
                ->name('dashboard');
        });
    });
});
