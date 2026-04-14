<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])
            ->whereIn('status', ['proses kembali', 'kembali']);

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pinjam', (int)$request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pinjam', (int)$request->tahun);
        } else {
            $query->whereYear('tanggal_pinjam', date('Y'));
        }

        $pengembalian = $query->latest()->paginate(10);
        return view('pengembalian', compact('pengembalian'));
    }

    public function store(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        return DB::transaction(function () use ($pinjam) {
            // Panggil rumus denda dari Model (Satu pintu)
            $nominalDenda = $pinjam->hitungDendaOtomatis();

            // Update status di tabel Peminjaman
            $pinjam->update([
                'status' => 'kembali',
                'denda'  => $nominalDenda,
                'tanggalkembali' => now()->toDateString(),
            ]);

            // Catat ke tabel pengembalian untuk log histori
            DB::table('pengembalian')->updateOrInsert(
                ['idpinjam' => $pinjam->idpinjam],
                [
                    'tanggalkembali' => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );


            $pesan = 'Buku berhasil diterima!';
            if ($nominalDenda > 0) {
                $pesan .= " Denda Rp " . number_format($nominalDenda, 0, ',', '.') . " telah dicatat.";
            }

            return redirect()->route('pengembalian.index')->with('success', $pesan);
        });
    }

    public function ajukan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        if ($pinjam->status !== 'Dipinjam') {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $pinjam->update(['status' => 'proses kembali']);
        return redirect()->back()->with('success', 'Pengembalian diajukan! Temui petugas.');
    }
}
