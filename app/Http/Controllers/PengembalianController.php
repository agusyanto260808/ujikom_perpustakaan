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

    public function store(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Cek agar tidak dobel input
        $sudahKembali = DB::table('pengembalian')->where('idpinjam', $id)->exists();
        if ($sudahKembali) {
            return redirect()->route('pengembalian.index')->with('error', 'Data ini sudah diproses sebelumnya.');
        }

        return DB::transaction(function () use ($pinjam) {
            // 1. Ambil Tanggal Jatuh Tempo (Hanya Tanggal, abaikan jam)
            $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();
            // 2. Ambil Tanggal Hari Ini (Hanya Tanggal)
            $hariIni = \Carbon\Carbon::now()->startOfDay();

            $nominalDenda = 0;
            $selisihHari = 0;

            // 3. Logika Perhitungan Denda
            // Menggunakan diffInDays dengan parameter kedua 'false' agar mendapatkan angka murni
            if ($hariIni->gt($jatuhTempo)) {
                $selisihHari = $hariIni->diffInDays($jatuhTempo);
                $nominalDenda = $selisihHari * 2000;
            }

            // 4. Simpan ke tabel pengembalian
            $idKembali = DB::table('pengembalian')->insertGetId([
                'idpinjam'       => $pinjam->idpinjam,
                'tanggalkembali' => now(), // Waktu asli pengembalian
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 5. Simpan ke tabel denda JIKA ada keterlambatan
            if ($nominalDenda > 0) {
                DB::table('denda')->insert([
                    'idpengembalian' => $idKembali,
                    'jumlah'         => $nominalDenda,
                    'hari_terlambat' => $selisihHari,
                    'tarif_per_hari' => 2000,
                    'status'         => 'Belum Lunas',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // 6. Update status di tabel peminjaman & Tambah Stok
            $pinjam->update(['status' => 'kembali']);

            if ($pinjam->buku) {
                $pinjam->buku->increment('stok', $pinjam->jumlah);
            }

            $pesan = $nominalDenda > 0
                ? "Buku diterima. Terlambat $selisihHari hari, denda otomatis dicatat: Rp " . number_format($nominalDenda, 0, ',', '.')
                : "Buku diterima tepat waktu. Stok buku bertambah.";

            return redirect()->route('pengembalian.index')->with('success', $pesan);
        });
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
            'status' => 'proses kembali',

        ]);

        return redirect()->back()->with('success', 'Pengembalian berhasil diajukan! Silakan temui petugas untuk menyerahkan buku.');
    }
}
