<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // App\Http\Controllers\LaporanController.php

    public function index(Request $request)
    {
        // Lakukan casting ke integer langsung saat mengambil input
        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));

        // Sekarang Carbon tidak akan protes karena menerima Integer
        $nama_bulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

        // 1. Data untuk Tab Peminjaman ($laporan)
        $laporan = Peminjaman::with(['user', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->get();

        // 2. Data untuk Tab Pengembalian ($laporanKembali)
        $laporanKembali = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->whereHas('pengembalian', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggalkembali', $bulan)
                    ->whereYear('tanggalkembali', $tahun);
            })
            ->get();

        // 3. Data untuk Tab Inventaris ($buku_all)
        $buku_all = Buku::all();

        return view('laporan', compact(
            'laporan',
            'laporanKembali',
            'buku_all',
            'bulan',
            'tahun',
            'nama_bulan'
        ));
    }
}
