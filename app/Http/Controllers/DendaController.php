<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DendaController extends Controller
{
    public function index()
    {
        // Mengambil data denda beserta relasi pengembalian -> peminjaman -> user & buku
        $dataDenda = Denda::with(['pengembalian.peminjaman.user', 'pengembalian.peminjaman.buku'])
            ->latest()
            ->paginate(10);

        return view('denda', compact('dataDenda'));
    }

    public function destroy($id)
    {
        DB::table('denda')->where('iddenda', $id)->delete();
        return redirect()->back()->with('success', 'Riwayat denda berhasil dihapus.');
    }

    public function konfirmasiLunas($id)
    {
        DB::table('denda')
            ->where('iddenda', $id)
            ->update([
                'status' => 'Lunas',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Pembayaran denda telah dikonfirmasi!');
    }
}
