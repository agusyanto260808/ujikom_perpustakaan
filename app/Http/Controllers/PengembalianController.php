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
        // Mengambil data yang sedang diajukan DAN yang sudah selesai agar ada riwayatnya
        $pengembalian = Peminjaman::with(['user', 'buku'])
            ->whereIn('status', ['proses kembali', 'kembali'])
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10);

        return view('pengembalian', compact('pengembalian'));
    }

    public function store(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // --- PROTEKSI 1: CEK STATUS ---
        // Jika status sudah 'kembali', jangan jalankan increment lagi!
        if ($pinjam->status === 'kembali') {
            return redirect()->route('pengembalian.index')->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($pinjam) {
                // UPDATE STATUS (Lakukan ini pertama)
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
                    // Gunakan increment sesuai JUMLAH yang dipinjam di data ini
                    $pinjam->buku->increment('stok', $pinjam->jumlah);
                }
            });

            return redirect()->route('pengembalian.index')->with('success', 'Buku berhasil diterima! Stok bertambah ' . $pinjam->jumlah);
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

        // LOGIKA TAMBAHAN: Hitung denda lagi di sini untuk keamanan
        $tgl_kembali = now();
        $tgl_deadline = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);
        $dendaPerHari = 90000; // Sesuaikan dengan nominal denda Anda

        if ($tgl_kembali->gt($tgl_deadline)) {
            $selisih = $tgl_kembali->diffInDays($tgl_deadline);
            $totalDenda = $selisih * $dendaPerHari;

            if ($totalDenda > 0) {
                return redirect()->back()->with('error', 'Gagal mengajukan! Anda memiliki denda yang belum dibayar.');
            }
        }

        // Jika tidak ada denda, baru update status agar muncul di halaman admin
        $pinjam->update([
            'status' => 'proses kembali'
        ]);

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Berhasil diajukan! Silakan serahkan buku ke petugas.');
    }
}
