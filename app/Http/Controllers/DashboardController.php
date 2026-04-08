<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ======================
        // STATISTIK (Data Nyata)
        // ======================
        $totalBuku = Buku::count();

        // Menghitung hanya user dengan role anggota
        $totalUser = User::where('role', 'anggota')->count();

        // Total yang sedang dipinjam (Aktif)
        // Termasuk status 'dipinjam' dan 'proses kembali' agar data sinkron dengan dashboard
        $totalPinjam = Peminjaman::whereIn('status', ['dipinjam', 'proses kembali'])->count();

        // Total yang sudah beres (Riwayat)
        $totalKembali = Peminjaman::whereIn('status', ['kembali', 'selesai', 'lunas'])->count();

        // ======================
        // DATA CHART (Urut Bulan)
        // ======================
        $peminjamanPerBulan = Peminjaman::selectRaw('
                MONTH(tanggal_pinjam) as bulan_angka,
                MONTHNAME(tanggal_pinjam) as bulan,
                COUNT(*) as total
            ')
            ->groupBy('bulan_angka', 'bulan')
            ->orderBy('bulan_angka')
            ->get();

        $labels = $peminjamanPerBulan->pluck('bulan');
        $values = $peminjamanPerBulan->pluck('total');

        // ======================
        // LOGIKA ROLE VIEW
        // ======================
        // Kita simpan datanya dalam satu array agar tidak menulis ulang compact dua kali
        $data = [
            'totalBuku'    => $totalBuku,
            'totalUser'    => $totalUser,
            'totalPinjam'  => $totalPinjam,
            'totalKembali' => $totalKembali,
            'labels'       => $labels,
            'values'       => $values,
        ];

        if (auth()->user()->role === 'anggota') {
            return view('dashboard_user', $data);
        }

        return view('dashboard', $data);
    }
}
