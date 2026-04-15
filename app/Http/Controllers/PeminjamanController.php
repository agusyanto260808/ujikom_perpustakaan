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
        $iduser = auth()->id();

        // 1. Cek Denda (Pastikan denda > 0 DAN belum dibayar)
        $hasUnpaidFine = Peminjaman::where('iduser', $iduser)
            ->where('denda', '>', 0) // Tambahkan pengecekan nominal denda
            ->where('status_bayar', 'belum')
            ->exists();

        if ($hasUnpaidFine) {
            // Jika lewat URL langsung, kita lempar balik dengan pesan error
            return redirect()->back()->with('error', 'Akses terkunci! Selesaikan denda Anda terlebih dahulu.');
        }
        // CATATAN: Pengecekan $hasPendingReturn DIHAPUS agar bisa pinjam buku lain

        // 2. Validasi input
        $request->validate([
            'idbuku' => 'required|exists:buku,idbuku',
            'tanggal_kembali' => 'required|date|after:today',
            'jumlah' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($request->idbuku);

        // 3. Cek stok
        if ($buku->stok_tersedia < $request->jumlah) {
            return redirect()->back()->with('error', 'Maaf, stok yang tersedia tidak mencukupi.');
        }

        // 4. Simpan data peminjaman
        Peminjaman::create([
            'iduser' => $iduser,
            'idbuku' => $request->idbuku,
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => $request->tanggal_kembali,
            'status' => 'Menunggu',
            'jumlah' => $request->jumlah,
            'status_bayar' => 'lunas',
        ]);

        return redirect()->route('riwayat_peminjaman.index')->with('success', 'Permintaan pinjam berhasil dikirim!');
    }
    public function update(Request $request, $id)
    {
        return \DB::transaction(function () use ($request, $id) {
            $peminjaman = Peminjaman::with('buku')->findOrFail($id);
            $statusBaru = $request->status;
            $statusLama = $peminjaman->status;

            if ($statusBaru == 'Ditolak') {
                // Ambil pesan dari input form petugas (pastikan name di form adalah 'pesan')
                $peminjaman->pesan = $request->pesan;
            }
            // 2. Logika Jika Disetujui (Dipinjam)
            if ($statusBaru == 'Dipinjam') {
                $peminjaman->pesan = null; // Bersihkan pesan jika sebelumnya ada
            }

            // 3. Logika Jika Kembali (Selesai)
            // Di dalam function update() pada PeminjamanController
            if ($statusBaru == 'Kembali' && $statusLama != 'Kembali') {
                $jatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->startOfDay();
                $hariIni = now()->startOfDay();

                if ($hariIni->gt($jatuhTempo)) {
                    $selisihHari = $hariIni->diffInDays($jatuhTempo);
                    $nominalDenda = abs($selisihHari * 2000);

                    $peminjaman->denda = $nominalDenda;
                    // PENTING: Jika ada denda, status bayar harus 'belum' agar terblokir
                    $peminjaman->status_bayar = 'belum';
                } else {
                    $peminjaman->denda = 0;
                    $peminjaman->status_bayar = 'lunas';
                }
            }

            $peminjaman->status = $statusBaru;
            $peminjaman->save();

            $pesanFlash = "Transaksi berhasil diperbarui.";
            if ($statusBaru == 'Ditolak') $pesanFlash = "Peminjaman telah ditolak.";

            return back()->with('success', $pesanFlash);
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
        // Saat halaman ini dibuka, semua notifikasi yang belum dibaca dianggap "sudah dibaca"
        \App\Models\Peminjaman::where('iduser', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $peminjaman = \App\Models\Peminjaman::with(['buku', 'user'])
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
    public function markAsRead()
    {
        \App\Models\Peminjaman::where('iduser', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['status' => 'success']);
    }


    public function lunasDenda($id) // Gunakan lunasDenda atau lunas_denda
    {
        // Opsional: Cek apakah yang akses adalah Petugas/Admin
        if (auth()->user()->role == 'Siswa') {
            return back()->with('error', 'Anda tidak memiliki akses untuk aksi ini.');
        }

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status_bayar' => 'lunas'
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi.');
    }
}
