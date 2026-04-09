<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{


    public function index(Request $request)
    {
        // Memulai query dengan eager loading untuk user dan buku
        $query = Peminjaman::with(['user', 'buku']);

        // 1. Filter berdasarkan Bulan (Jika ada input)
        if ($request->filled('bulan')) {
            // CRITICAL: Paksa konversi ke integer (int) untuk menghindari error Carbon
            $bulan = (int) $request->bulan;
            $query->whereMonth('tanggal_pinjam', $bulan);
        }

        // 2. Filter berdasarkan Tahun
        if ($request->filled('tahun')) {
            $tahun = (int) $request->tahun;
            $query->whereYear('tanggal_pinjam', $tahun);
        } else {
            // Default menampilkan tahun sekarang jika tidak ada filter tahun
            $query->whereYear('tanggal_pinjam', date('Y'));
        }

        // Mengambil data dengan pagination dan mempertahankan parameter filter di URL
        $peminjaman = $query->latest('idpinjam')->paginate(10);

        return view('peminjaman', compact('peminjaman'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'tanggal_kembali' => 'required|date|after:today', // Sesuaikan name-nya
            'jumlah' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($request->idbuku);

        if ($buku->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        DB::transaction(function () use ($request, $buku) {
            $buku->decrement('stok', $request->jumlah);

            Peminjaman::create([
                'iduser' => auth()->id(),
                'idbuku' => $request->idbuku,
                'tanggal_pinjam' => now(),
                'tanggal_jatuh_tempo' => $request->tanggal_kembali, // Gunakan name yang benar
                'status' => 'Menunggu',
                'jumlah' => $request->jumlah,
            ]);
        });

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Berhasil!');
    }
    public function update(Request $request, $id)
    {
        return \DB::transaction(function () use ($request, $id) {
            $peminjaman = Peminjaman::with('buku')->findOrFail($id);
            $statusBaru = $request->status;
            $statusLama = $peminjaman->status;

            // --- LOGIKA SAAT BUKU DIKEMBALIKAN ---
            // if ($statusBaru == 'Kembali' && $statusLama != 'Kembali') {
            //     $peminjaman->buku->increment('stok', $peminjaman->jumlah);

            //     // Set tanggal pengembalian realita adalah hari ini
            //     $peminjaman->tanggalkembali = now()->format('Y-m-d');

            //     // --- HITUNG DENDA OTOMATIS ---
            //     $jatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo);
            //     $hariIni = now();

            //     if ($hariIni->gt($jatuhTempo)) {
            //         $selisihHari = $hariIni->diffInDays($jatuhTempo);
            //         $dendaPerHari = 2000; // Contoh: Rp 2.000 per hari
            //         $peminjaman->denda = $selisihHari * $dendaPerHari;
            //     } else {
            //         $peminjaman->denda = 0;
            //     }
            // }

            // --- LOGIKA JIKA ADMIN MENOLAK ---
            if ($statusBaru == 'Ditolak' && $statusLama == 'Menunggu') {
                $peminjaman->buku->increment('stok', $peminjaman->jumlah);
            }

            $peminjaman->status = $statusBaru;
            $peminjaman->save();


            return back()->with('success', "Status diperbarui. " . ($peminjaman->denda > 0 ? "User dikenakan denda Rp " . number_format($peminjaman->denda, 0, ',', '.') : ""));
        });
    }
    public function show($id)
    {
        $item = Buku::findOrFail($id);
        return view('pinjam_buku', compact('item'));
    }

    // Halaman Riwayat untuk Siswa/User
    public function riwayat()
    {
        $peminjaman = Peminjaman::with(['buku', 'user', 'pengembalian']) // TAMBAHKAN pengembalian DI SINI
            ->where('iduser', auth()->id())
            ->latest()
            ->paginate(10);

        return view('riwayat_peminjaman', compact('peminjaman'));
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
