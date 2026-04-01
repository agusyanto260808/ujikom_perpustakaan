<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini agar lebih rapi

class BukuController extends Controller
{
    public function index()
    {
        // Ganti get() menjadi paginate(jumlah_data_per_halaman)
        // Misalnya kita tampilkan 10 data per halaman
        $buku = Buku::latest()->paginate(10);

        return view('buku', compact('buku'));
    }
    public function create()
    {
        return view('buku_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'   => 'required|max:225',
            'penulis' => 'required|max:225',
            'penerbit' => 'required|max:225',
            'tahun'   => 'required|numeric',
            'stok'    => 'required|numeric',
            'gambar'  => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Folder penyimpanan disesuaikan dengan view (buku_covers)
            $data['gambar'] = $request->file('gambar')->store('buku_covers', 'public');
        }

        // Mengambil ID user yang sedang login untuk kolom iduser
        $data['iduser'] = auth()->id();

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Laravel akan mencari berdasarkan primary key yang didefinisikan di Model
        $buku = Buku::findOrFail($id);
        return view('buku_edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'   => 'required|max:225',
            'penulis' => 'required|max:225',
            'penerbit' => 'required|max:225',
            'tahun'   => 'required|numeric',
            'stok'    => 'required|numeric',
            'gambar'  => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $buku = Buku::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada sebelum ganti yang baru
            if ($buku->gambar) {
                Storage::disk('public')->delete($buku->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('buku_covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Hapus file fisik gambar
        if ($buku->gambar) {
            Storage::disk('public')->delete($buku->gambar);
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
