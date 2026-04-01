<?php

namespace App\Http\Controllers;

// Pastikan kedua model ini di-import
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Menampilkan halaman laporan denda
     */
    public function index()
    {
        // Jika data denda ada di tabel peminjaman, gunakan Peminjaman::all()
        // Kita simpan ke variabel $pengembalian supaya sesuai dengan yang diminta di Blade
        $pengembalian = Peminjaman::all();

        // Melempar variabel $pengembalian ke view denda.blade.php
        return view('denda', compact('pengembalian'));
    }

    /**
     * Opsi: Jika ingin menghapus/meriset denda
     */
    public function destroy($id)
    {
        $denda = Peminjaman::findOrFail($id);

        // Update nilai denda jadi 0
        $denda->update(['denda' => 0]);

        return redirect()->back()->with('success', 'Riwayat denda berhasil diperbarui.');
    }
}
