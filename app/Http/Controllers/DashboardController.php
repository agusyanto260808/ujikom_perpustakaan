<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Fungsi untuk Admin
    public function index()
    {
        return $this->getDashboardData('dashboard');
    }

    // Fungsi untuk User/Anggota (Ini yang dicari oleh error di gambar)
    public function userIndex()
    {
        return $this->getDashboardData('dashboard_user');
    }

    // Fungsi pembantu agar kode tidak duplikat
    private function getDashboardData($view)
    {
        $totalBuku = Buku::count();
        $totalUser = User::where('role', 'anggota')->count();
        $totalPinjam = Peminjaman::whereIn('status', ['dipinjam', 'proses kembali'])->count();
        $totalKembali = Peminjaman::whereIn('status', ['kembali', 'selesai', 'lunas'])->count();

        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('l');
            $values[] = Peminjaman::whereDate('tanggal_pinjam', $date->format('Y-m-d'))->count();
        }

        return view($view, [
            'totalBuku'    => $totalBuku,
            'totalUser'    => $totalUser,
            'totalPinjam'  => $totalPinjam,
            'totalKembali' => $totalKembali,
            'labels'       => $labels,
            'values'       => $values,
        ]);
    }
}
