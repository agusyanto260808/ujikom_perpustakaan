<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- WAJIB TAMBAHKAN INI

class PengembalianController extends Controller
{
    public function index()
    {
        // Ambil data yang statusnya 'proses kembali'
        $pengembalian = Peminjaman::with(['user', 'buku'])
            ->where('status', 'proses kembali')
            ->paginate(10);

        return view('pengembalian', compact('pengembalian'));
    }

    public function store(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        try {
            DB::transaction(function () use ($pinjam) {
                // UPDATE STATUS
                $pinjam->update(['status' => 'kembali']);

                // CATAT KE TABEL PENGEMBALIAN
                DB::table('pengembalian')->insert([
                    'tanggalkembali' => now(),
                    'idpinjam'       => $pinjam->idpinjam,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // KEMBALIKAN STOK
                if ($pinjam->buku) {
                    $pinjam->buku->increment('stok', $pinjam->jumlah);
                }
            });

            return redirect()->route('pengembalian.index')->with('success', 'Buku berhasil diterima!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function kembaliBukuUser()
    {
        // Menggunakan Auth::id() sekarang sudah aman karena namespace sudah di-import
        $peminjaman = Peminjaman::with('buku')
            ->where('iduser', Auth::id())
            ->where('status', 'Dipinjam') // Pastikan case-sensitive sesuai DB Anda
            ->latest()
            ->get();

        return view('kembali_buku', compact('peminjaman'));
    }

    public function ajukan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'proses kembali'
        ]);

        return redirect()->route('kembali_buku.index')->with('success', 'Berhasil diajukan! Silakan serahkan buku ke petugas.');
    }
}
