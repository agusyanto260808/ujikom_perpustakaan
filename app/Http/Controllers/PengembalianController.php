<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request di sini
    {
        // 1. Inisialisasi query
        $query = Peminjaman::with(['user', 'buku'])
            ->whereIn('status', ['proses kembali', 'kembali']);

        // 2. Filter berdasarkan Bulan
        if ($request->filled('bulan')) {
            $bulan = (int) $request->bulan; // Konversi ke integer agar Carbon tidak error
            $query->whereMonth('tanggal_pinjam', $bulan);
        }

        // 3. Filter berdasarkan Tahun
        if ($request->filled('tahun')) {
            $tahun = (int) $request->tahun;
            $query->whereYear('tanggal_pinjam', $tahun);
        } else {
            // Default ke tahun sekarang agar data tidak menumpuk
            $query->whereYear('tanggal_pinjam', date('Y'));
        }

        // 4. Ambil data dengan pagination dan urutan terbaru
        $pengembalian = $query->latest()->paginate(10);

        // 5. Kirim ke view
        return view('pengembalian', compact('pengembalian'));
    }

    // PengembalianController.php

    // File: App\Http\Controllers\PengembalianController.php
    public function store(Request $request, $id)
    {
        $pinjam = \App\Models\Peminjaman::findOrFail($id);

        return \DB::transaction(function () use ($pinjam) {
            // --- LOGIKA PERHITUNGAN DENDA ---
            $tglKembali = now(); // Tanggal hari ini
            $tglJatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);

            $hariTerlambat = 0;
            $nominalDenda = 0;
            $tarifPerHari = 2000;

            // Cek jika tgl kembali melewati jatuh tempo
            if ($tglKembali->gt($tglJatuhTempo)) {
                // Hitung selisih hari
                $hariTerlambat = $tglKembali->diffInDays($tglJatuhTempo);
                $nominalDenda = $hariTerlambat * $tarifPerHari;
            }
            // --------------------------------

            // 1. Simpan ke tabel PENGEMBALIAN
            $idKembali = \DB::table('pengembalian')->insertGetId([
                'idpinjam'       => $pinjam->idpinjam,
                'tanggalkembali' => $tglKembali->toDateString(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2. Simpan ke tabel DENDA (Hanya jika terlambat)
            if ($nominalDenda > 0) {
                \DB::table('denda')->insert([
                    'idpengembalian' => $idKembali,
                    'jumlah'         => $nominalDenda,
                    'hari_terlambat' => $hariTerlambat,
                    'status'         => 'belum_bayar',
                    'tarif_per_hari' => $tarifPerHari,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // 3. Update status peminjaman
            $pinjam->update([
                'status' => 'kembali',
            ]);


            $pesan = 'Buku berhasil diterima!';
            if ($nominalDenda > 0) {
                $pesan .= " Terlambat {$hariTerlambat} hari. Denda Rp " . number_format($nominalDenda, 0, ',', '.') . " telah dicatat.";
            }

            return redirect()->route('pengembalian.index')->with('success', $pesan);
        });
    }

    public function kembaliBukuUser()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('iduser', Auth::id())
            ->where('status', 'Dipinjam')
            ->latest()
            ->get();

        return view('kembali_buku', compact('peminjaman'));
    }

    // Fungsi untuk User mengajukan pengembalian
    public function ajukan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Validasi: Jika status sudah diproses, jangan ajukan dua kali
        if ($pinjam->status !== 'Dipinjam') {
            return redirect()->back()->with('error', 'Status buku tidak valid untuk dikembalikan.');
        }

        $pinjam->update([
            'status' => 'proses kembali',

        ]);

        return redirect()->back()->with('success', 'Pengembalian berhasil diajukan! Silakan temui petugas untuk menyerahkan buku.');
    }
}
