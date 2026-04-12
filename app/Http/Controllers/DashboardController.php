<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // app/Http/Controllers/DashboardController.php

    public function index()
    {
        // 1. Hitung statistik dasar
        $totalBuku = Buku::count();
        $totalUser = User::where('role', 'anggota')->count();
        $totalPinjam = Peminjaman::whereIn('status', ['dipinjam', 'proses kembali'])->count();
        $totalKembali = Peminjaman::whereIn('status', ['kembali', 'selesai', 'lunas'])->count();

        // 2. Ambil data chart (untuk grafik tren literasi)
        $peminjamanPerBulan = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan_angka, MONTHNAME(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->groupBy('bulan_angka', 'bulan')
            ->orderBy('bulan_angka')
            ->get();

        $labels = $peminjamanPerBulan->pluck('bulan');
        $values = $peminjamanPerBulan->pluck('total');

        // 3. Ambil riwayat peminjaman
        $riwayatPinjam = Peminjaman::with('buku')
            ->where('iduser', auth()->id()) // Ganti 'id' menjadi 'iduser' sesuai database
            ->latest()
            ->take(5)
            ->get();

        // 4. Bungkus data untuk dikirim ke view
        $data = [
            'totalBuku'     => $totalBuku,
            'totalUser'     => $totalUser,
            'totalPinjam'   => $totalPinjam,
            'totalKembali'  => $totalKembali,
            'labels'        => $labels,
            'values'        => $values,
            'riwayatPinjam' => $riwayatPinjam,
        ];

        // 5. Cek Role dan Return View
        if (auth()->user()->role === 'anggota') {
            return view('dashboard_user', $data);
        }

        return view('dashboard', $data);
    }
    public function userIndex()
    {
        return view('dashboard_user'); // Pastikan Anda punya file resources/views/dashboard_user.blade.php
    }
}
