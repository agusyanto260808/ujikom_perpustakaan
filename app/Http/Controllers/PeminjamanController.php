<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index()
    {
        // Menggunakan eager loading 'buku' agar tidak berat (N+1 Problem)
        $peminjamans = Peminjaman::with('buku')->latest()->paginate(10);
        return view('peminjaman', compact('peminjamans'));
    }

    // Menampilkan form tambah (Opsional, jika kamu pakai halaman terpisah)
    public function create()
    {
        // Hanya ambil buku yang stoknya lebih dari 0
        $bukus = Buku::where('stok', '>', 0)->get();
        return view('peminjaman_create', compact('bukus'));
    }

    // Simpan data peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'nama_peminjam' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        // Database Transaction agar stok & log tetap sinkron jika ada error
        DB::transaction(function () use ($request) {
            // 1. Catat Peminjaman
            Peminjaman::create([
                'idbuku' => $request->idbuku,
                'nama_peminjam' => $request->nama_peminjam,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'Dipinjam',
            ]);

            // 2. Kurangi stok buku
            $buku = Buku::findOrFail($request->idbuku);
            $buku->decrement('stok');
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    // Update status (Proses Pengembalian)
    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($id) {
            $pinjam = Peminjaman::findOrFail($id);

            if ($pinjam->status == 'Dipinjam') {
                // 1. Ubah status jadi Kembali
                $pinjam->update(['status' => 'Kembali']);

                // 2. Tambah stok buku kembali
                $buku = Buku::findOrFail($pinjam->idbuku);
                $buku->increment('stok');
            }
        });

        return redirect()->back()->with('success', 'Buku telah dikembalikan, stok diperbarui!');
    }

    // Hapus log peminjaman
    public function destroy($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Opsional: Jika log dihapus saat status masih 'Dipinjam', stok dikembalikan dulu?
        // Tapi biasanya log dihapus hanya untuk data lama.
        $pinjam->delete();

        return redirect()->back()->with('success', 'Log peminjaman berhasil dihapus.');
    }
}
