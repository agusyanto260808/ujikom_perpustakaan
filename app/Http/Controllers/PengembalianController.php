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
    public function index()
    {
        // Mengambil data yang sedang diajukan (proses kembali) 
        // DAN yang sudah selesai (kembali) agar ada riwayatnya
        $pengembalian = Peminjaman::with(['user', 'buku'])
            ->whereIn('status', ['proses kembali', 'kembali'])
            ->latest()
            ->paginate(10);

        return view('pengembalian', compact('pengembalian'));
    }

    public function store(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status === 'kembali') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        try {
            $nominalDenda = $request->input('nominal_denda', 0);

            // Hitung selisih hari untuk tabel denda
            $tglJatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);
            $hariIni = now()->startOfDay();
            $selisihHari = $tglJatuhTempo->diffInDays($hariIni, false);
            $hariTerlambat = $selisihHari > 0 ? $selisihHari : 0;

            DB::transaction(function () use ($pinjam, $nominalDenda, $hariTerlambat) {
                // 1. Update status di tabel peminjaman
                $pinjam->update(['status' => 'kembali']);

                // 2. Insert ke tabel pengembalian dan AMBIL ID-nya
                $idKembali = DB::table('pengembalian')->insertGetId([
                    'idpinjam'       => $pinjam->idpinjam,
                    'tanggalkembali' => now(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // 3. Insert ke tabel denda (Sesuaikan dengan screenshot kolom Anda)
                if ($nominalDenda > 0) {
                    DB::table('denda')->insert([
                        'idpengembalian' => $idKembali, // Sesuai kolom di DB
                        'jumlah'         => $nominalDenda, // Kolom 'jumlah' di DB
                        'hari_terlambat' => $hariTerlambat,
                        'status'         => 'Lunas',
                        'tarif_per_hari' => 2000,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                // 4. Kembalikan Stok Buku
                if ($pinjam->buku) {
                    $pinjam->buku->increment('stok', $pinjam->jumlah);
                }
            });

            return redirect()->route('pengembalian.index')
                ->with('success', 'Buku berhasil diterima dan denda dicatat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    // Fungsi untuk User melihat daftar buku yang bisa dikembalikan
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
            'status' => 'proses kembali', // Huruf kecil
            'tanggalkembali' => now()
        ]);

        return redirect()->back()->with('success', 'Pengembalian berhasil diajukan! Silakan temui petugas untuk menyerahkan buku.');
    }
}
