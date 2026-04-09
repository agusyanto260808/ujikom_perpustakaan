<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Pengembalian;
use App\Models\Denda;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'pengembalian.denda']);

        // Filter Bulan & Tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pinjam', (int)$request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pinjam', (int)$request->tahun);
        } else {
            $query->whereYear('tanggal_pinjam', date('Y'));
        }

        $laporan = $query->latest()->get();

        return view('laporan', compact('laporan'));
    }
}
