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
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // DATA UNTUK TAB PEMINJAMAN
        $laporan = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->get();

        // DATA UNTUK TAB PENGEMBALIAN & DENDA
        $laporanKembali = Peminjaman::with(['user', 'buku', 'pengembalian.denda'])
            ->whereHas('pengembalian', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggalkembali', $bulan)
                    ->whereYear('tanggalkembali', $tahun);
            })
            ->get();

        // MENGHITUNG TOTAL BUKU YANG DIPINJAM (Berdasarkan data yang dikembalikan)
        // Pastikan kolom di database Anda namanya 'jumlah'
        $total_buku_kembali = $laporanKembali->sum('jumlah');

        $buku_all = Buku::all();
        $nama_bulan = Carbon::create()->month((int)$bulan)->translatedFormat('F');

        return view('laporan', compact(
            'laporan',
            'laporanKembali',
            'buku_all',
            'bulan',
            'nama_bulan',
            'tahun',
            'total_buku_kembali' // Kirim variabel ini ke view
        ));
    }
}
