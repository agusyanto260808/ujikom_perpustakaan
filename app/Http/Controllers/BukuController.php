<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mengambil data buku
        $query = Buku::query();

        // Jika ada pencarian, filter berdasarkan judul atau penulis
        if ($search) {
            $query->where('judul', 'like', "%$search%")
                ->orWhere('penulis', 'like', "%$search%");
        }

        $buku = $query->latest()->paginate(12);

        // CEK: Jika yang login adalah 'anggota', arahkan ke tampilan katalog perpustakaan
        if (auth()->user()->role === 'anggota') {
            return view('katalog_buku', compact('buku'));
        }

        // Jika admin/petugas, arahkan ke tabel kelola buku (tampilan Master Buku)
        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'required|image'
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $data['iduser'] = auth()->id();
        \App\Models\Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil disimpan');
    }

    public function update(Request $request, $id)

    {
        $buku = buku::findOrFail($id);

        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Data berhasil diupdate');
    }
    // Tambahkan ini di dalam class BukuController
    public function edit($id)
    {
        // Cari data buku berdasarkan ID (sesuaikan primary key Anda, misal: idbuku)
        $buku = \App\Models\Buku::where('idbuku', $id)->firstOrFail();

        // Kirim data buku ke view edit
        return view('buku.edit', compact('buku'));
    }
    public function show($id)
    {
        // Mencari berdasarkan idbuku karena primary key sudah diganti
        $item = Buku::where('idbuku', $id)->firstOrFail();
        return view('pinjam_buku', compact('item')); // Sesuaikan dengan nama file blade kamu
    }
    public function destroy($id)
    {
        buku::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus');
    }
}
