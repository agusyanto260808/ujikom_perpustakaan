<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{


    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->paginate(10);

        // Ganti 'pengembalian' menjadi nama file blade peminjaman Anda
        return view('peminjaman', compact('peminjaman'));
    }

    // ... method ajukan dan store sudah benar secara logika ...


    // ... method lainnya (store, update, destroy) tetap seperti sebelumnya


    public function store(Request $request)
    {
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'tanggal_kembali' => 'required|date|after:today',
            'jumlah' => 'required|integer|min:1',
        ]);

        Peminjaman::create([
            'iduser' => auth()->id(),
            'idbuku' => $request->idbuku,
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => $request->tanggal_kembali,
            'status' => 'Menunggu',
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Permintaan pinjam berhasil dikirim!');
    }

    public function show($id)
    {
        $item = Buku::findOrFail($id);
        return view('pinjam_buku', compact('item'));
    }

    // Halaman Riwayat untuk Siswa/User
    public function riwayat()
    {
        // Mengambil data peminjaman milik user yang sedang login
        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('iduser', auth()->id())
            ->latest()
            ->paginate(10);

        return view('riwayat_peminjaman', compact('peminjaman'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        $statusBaru = $request->status;
        $statusLama = $peminjaman->status;

        // Logika Pengurangan Stok (Saat Admin menyetujui)
        if ($statusBaru == 'Dipinjam' && $statusLama == 'Menunggu') {
            if ($peminjaman->buku->stok < $peminjaman->jumlah) {
                return back()->with('error', 'Stok buku tidak mencukupi!');
            }
            $peminjaman->buku->decrement('stok', $peminjaman->jumlah);
        }

        $peminjaman->status = $statusBaru;
        $peminjaman->save();

        return back()->with('success', "Status diperbarui menjadi $statusBaru.");
    }
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
