<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Method untuk Admin (Daftar semua peminjaman)
    public function index()
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->latest()->paginate(10);
        return view('peminjaman', compact('peminjaman'));
    }

    // Method untuk Proses Simpan Pinjaman (Siswa)
    public function store(Request $request)
    {
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'tanggal_kembali' => 'required|date', // Ini nama dari input form
            'jumlah' => 'required|integer|min:1',
        ]);

        Peminjaman::create([
            'iduser' => auth()->id(),
            'idbuku' => $request->idbuku,
            'tgl_pinjam' => now(),
            // PASTIKAN ini sesuai dengan fillable di Model (tanggal_jatuh_tempo)
            'tanggal_jatuh_tempo' => $request->tanggal_kembali,
            'status' => 'Menunggu',
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Permintaan pinjam berhasil dikirim!');
    }
    public function show($id)
    {
        $item = Buku::findOrFail($id);
        // Pastikan memanggil 'pinjam_buku', bukan 'detail_buku'
        return view('pinjam_buku', compact('item'));
    }
    // Method untuk Riwayat Pribadi (Siswa)
    public function riwayat()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('iduser', auth()->id())
            ->latest()
            ->get();

        return view('riwayat_peminjaman', compact('peminjaman'));
    }
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Ambil status dari input hidden di Blade
        $statusBaru = $request->status; // 'Dipinjam' atau 'Kembali'

        $peminjaman->update([
            'status' => $statusBaru
        ]);

        // Logika tambahan: Jika dikembalikan, stok buku ditambah lagi
        if ($statusBaru == 'Kembali') {
            $peminjaman->buku->increment('stok', 1); // Sesuaikan jumlahnya jika perlu
        }

        return back()->with('success', 'Status peminjaman berhasil diperbarui menjadi ' . $statusBaru);
    }
}
