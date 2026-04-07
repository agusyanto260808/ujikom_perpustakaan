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
        // Mengambil data dari database
        $totalBuku = \App\Models\Buku::count();
        $totalUser = \App\Models\User::count();
        $totalPinjam = \App\Models\Peminjaman::count();

        // Data untuk Chart
        $peminjamanPerBulan = \App\Models\Peminjaman::selectRaw('MONTHNAME(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->get();

        $labels = $peminjamanPerBulan->pluck('bulan');
        $values = $peminjamanPerBulan->pluck('total');

        // Pastikan variabel $totalBuku ada di dalam compact()
        return view('dashboard', compact('totalBuku', 'totalUser', 'totalPinjam', 'labels', 'values'));
    }
}
