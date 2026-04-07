<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Menampilkan halaman laporan denda dengan pagination
     */
    public function index()
    {
        // Ganti ::all() menjadi ::paginate()
        // Angka 10 berarti 10 data per halaman
        $pengembalian = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10);

        // Melempar variabel $pengembalian ke view denda.blade.php
        return view('denda', compact('pengembalian'));
    }

    /**
     * Update/Reset denda
     */
    public function destroy($id)
    {
        $denda = Peminjaman::findOrFail($id);
        $denda->update(['denda' => 0]);

        return redirect()->back()->with('success', 'Riwayat denda berhasil diperbarui.');
    }
}
