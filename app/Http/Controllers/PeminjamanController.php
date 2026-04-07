<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{


    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->paginate(10);

        // Ganti 'pengembalian' menjadi nama file blade peminjaman Anda
        return view('peminjaman', compact('peminjaman'));
    }


    // Tambahkan baris ini
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
    ];


    public function store(Request $request)
    {
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'tanggal_kembali' => 'required|date|after:today',
            'jumlah' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($request->idbuku);

        // --- PROTEKSI: CEK STOK DULU ---
        if ($buku->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Maaf, stok buku tidak mencukupi.');
        }

        \DB::transaction(function () use ($request, $buku) {
            // 1. Kurangi stok buku SEKARANG (saat dipesan) agar tidak dipinjam orang lain
            $buku->decrement('stok', $request->jumlah);

            // 2. Buat data peminjaman
            Peminjaman::create([
                'iduser' => auth()->id(),
                'idbuku' => $request->idbuku,
                'tanggal_pinjam' => now(),
                'tanggal_jatuh_tempo' => $request->tanggal_kembali,
                'status' => 'Menunggu', // User menunggu persetujuan admin
                'jumlah' => $request->jumlah,
            ]);
        });

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Permintaan pinjam berhasil dikirim! Stok buku telah dikurangi.');
    }

    public function update(Request $request, $id)
    {
        return \DB::transaction(function () use ($request, $id) {
            $peminjaman = Peminjaman::with('buku')->findOrFail($id);
            $statusBaru = $request->status;
            $statusLama = $peminjaman->status;

            // --- LOGIKA SAAT BUKU DIKEMBALIKAN ---
            if ($statusBaru == 'Kembali' && $statusLama != 'Kembali') {
                $peminjaman->buku->increment('stok', $peminjaman->jumlah);

                // Set tanggal pengembalian realita adalah hari ini
                $peminjaman->tanggal_kembali = now()->format('Y-m-d');

                // --- HITUNG DENDA OTOMATIS ---
                $jatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo);
                $hariIni = now();

                if ($hariIni->gt($jatuhTempo)) {
                    $selisihHari = $hariIni->diffInDays($jatuhTempo);
                    $dendaPerHari = 2000; // Contoh: Rp 2.000 per hari
                    $peminjaman->denda = $selisihHari * $dendaPerHari;
                } else {
                    $peminjaman->denda = 0;
                }
            }

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
