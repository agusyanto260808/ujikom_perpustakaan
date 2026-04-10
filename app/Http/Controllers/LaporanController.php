<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan nama variabel $bulan agar konsisten
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $laporan = Peminjaman::with(['user', 'buku', 'pengembalian.denda'])
            ->where(function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_pinjam', $bulan)
                    ->whereYear('tanggal_pinjam', $tahun);
            })
            ->orWhereHas('pengembalian', function ($query) use ($bulan, $tahun) {
                // Menggunakan tanggalkembali sesuai struktur DB Anda
                $query->whereMonth('tanggalkembali', $bulan)
                    ->whereYear('tanggalkembali', $tahun);
            })
            ->get();

        $buku_all = Buku::all();
        $nama_bulan = Carbon::create()->month((int)$bulan)->translatedFormat('F');

        // Pastikan 'bulan' (bukan bulan_angka) ada di sini
        return view('laporan', compact('laporan', 'buku_all', 'bulan', 'nama_bulan', 'tahun'));
    }
}
