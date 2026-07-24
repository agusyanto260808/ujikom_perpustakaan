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
        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));
        $nama_bulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
        $search = $request->input('search');


        $query = Peminjaman::with(['user', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun);


        if ($search && auth()->user()->role == 'kep_perpus') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('buku', function ($queryBuku) use ($search) {
                    $queryBuku->where('judul', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($queryUser) use ($search) {
                        $queryUser->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $laporan = $query->get();

        // 2. Data untuk Tab Pengembalian (Ditambahkan pencarian juga jika perlu)
        $queryKembali = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->whereHas('pengembalian', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggalkembali', $bulan)
                    ->whereYear('tanggalkembali', $tahun);
            });

        if ($search && auth()->user()->role == 'kep_perpus') {
            $queryKembali->where(function ($q) use ($search) {
                $q->whereHas('buku', function ($qb) use ($search) {
                    $qb->where('judul', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $laporanKembali = $queryKembali->get();

        // 3. Data untuk Tab Inventaris
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
