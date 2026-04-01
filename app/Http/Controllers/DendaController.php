<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Menampilkan halaman laporan denda
     */
    public function index()
    {
        // Kita hanya mengambil data yang memiliki denda > 0
        // Eager load 'buku' untuk menampilkan judul buku di tabel
        $peminjamans = Peminjaman::with('buku')
            ->where('denda', '>', 0)
            ->latest()
            ->get();

        // Menghitung total denda untuk ringkasan di atas tabel
        $totalDenda = $peminjamans->sum('denda');

        return view('denda', compact('peminjamans', 'totalDenda'));
    }

    /**
     * Opsi: Jika ingin menghapus riwayat denda tertentu
     */
    public function destroy($id)
    {
        $denda = Peminjaman::findOrFail($id);

        // Kita tidak menghapus datanya, tapi meriset dendanya jadi 0 
        // atau biarkan saja sebagai histori permanen.
        // Di sini saya buatkan opsi update ke 0 jika diperlukan:
        $denda->update(['denda' => 0]);

        return redirect()->back()->with('success', 'Riwayat denda berhasil diperbarui.');
    }
}
