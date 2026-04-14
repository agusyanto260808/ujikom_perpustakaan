<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Dasar
        $totalBuku = Buku::count();
        $totalUser = User::where('role', 'anggota')->count();
        $totalPinjam = Peminjaman::whereIn('status', ['dipinjam', 'proses kembali'])->count();
        $totalKembali = Peminjaman::whereIn('status', ['kembali', 'selesai', 'lunas'])->count();

        // 2. Logika Grafik 7 Hari Terakhir (Sesuai Permintaan)
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->isoFormat('dddd'); // Menghasilkan: Senin, Selasa, dst.

            // Hitung jumlah peminjaman pada tanggal tersebut
            $count = Peminjaman::whereDate('tanggal_pinjam', $date->format('Y-m-d'))->count();
            $values[] = $count;
        }

        // 3. Riwayat Peminjaman (Opsional untuk Admin/User)
        $riwayatPinjam = Peminjaman::with('buku')
            ->when(auth()->user()->role === 'anggota', function ($query) {
                return $query->where('iduser', auth()->id());
            })
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'totalBuku'    => $totalBuku,
            'totalUser'    => $totalUser,
            'totalPinjam'  => $totalPinjam,
            'totalKembali' => $totalKembali,
            'labels'       => $labels, // Data hari untuk JS
            'values'       => $values, // Data jumlah untuk JS
            'riwayatPinjam' => $riwayatPinjam,
        ];

        if (auth()->user()->role === 'anggota') {
            return view('dashboard_user', $data);
        }

        return view('dashboard', $data);
    }
}
