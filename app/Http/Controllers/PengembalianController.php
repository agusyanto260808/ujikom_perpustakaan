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

        // Jika sudah status kembali, jangan diproses lagi
        if ($pinjam->status === 'kembali') {
            return redirect()->route('pengembalian.index')->with('info', 'Buku ini sudah dikonfirmasi sebelumnya.');
        }

        return DB::transaction(function () use ($pinjam) {
            // 1. Logika Perhitungan Denda
            $tglKembali = now()->startOfDay();
            $tglJatuhTempo = Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();

            $hariTerlambat = 0;
            $nominalDenda = 0;
            $tarifPerHari = 2000;

            if ($tglKembali->gt($tglJatuhTempo)) {
                $hariTerlambat = $tglKembali->diffInDays($tglJatuhTempo);
                $nominalDenda = $hariTerlambat * $tarifPerHari;
            }

            // 2. Gunakan updateOrInsert pada tabel pengembalian (Mencegah Duplicate Entry)
            // Ini akan mengecek idpinjam, jika ada maka update, jika tidak ada maka insert.
            DB::table('pengembalian')->updateOrInsert(
                ['idpinjam' => $pinjam->idpinjam],
                [
                    'tanggalkembali' => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Ambil ID pengembalian yang baru saja diproses
            $idKembali = DB::table('pengembalian')
                ->where('idpinjam', $pinjam->idpinjam)
                ->value('idpengembalian');

            // 3. Simpan ke tabel denda menggunakan updateOrInsert
            if ($nominalDenda > 0) {
                DB::table('denda')->updateOrInsert(
                    ['idpengembalian' => $idKembali],
                    [
                        'jumlah' => $nominalDenda,
                        'hari_terlambat' => $hariTerlambat,
                        'status' => 'belum_bayar',
                        'tarif_per_hari' => $tarifPerHari,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // 4. Update status peminjaman & Stok Buku
            $pinjam->update(['status' => 'kembali']);

            $buku = Buku::find($pinjam->idbuku);
            if ($buku) {
                $buku->increment('stok');
            }

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
