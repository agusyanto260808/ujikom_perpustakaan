<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /**
     * Menampilkan daftar buku yang sedang dipinjam (Belum Kembali)
     */
    public function index()
    {
        $pengembalian = Peminjaman::with('buku')
            ->where('status', 'Dipinjam')
            ->latest()
            ->paginate(10);

        return view('pengembalian', compact('pengembalian'));
    }

    /**
     * Proses pengembalian buku
     */
    public function store(Request $request, $id)
    {
        // Cari data peminjaman berdasarkan ID
        $pinjam = Peminjaman::findOrFail($id);

        // Pastikan statusnya memang masih dipinjam
        if ($pinjam->status !== 'Dipinjam') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        // Gunakan Transaction agar jika stok gagal update, status tidak berubah
        DB::transaction(function () use ($pinjam) {
            // 1. Update status peminjaman menjadi 'Kembali'
            $pinjam->update([
                'status' => 'Kembali',
                'tgl_kembali_realitas' => now(), // Opsional: catat tanggal asli pengembalian
            ]);

            // 2. Tambah kembali stok buku tersebut
            $buku = Buku::findOrFail($pinjam->idbuku);
            $buku->increment('stok');
        });

        return redirect()->route('peminjaman.index')->with('success', 'Buku "' . $pinjam->buku->judul . '" telah berhasil dikembalikan!');
    }
}
