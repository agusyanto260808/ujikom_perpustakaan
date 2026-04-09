<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori; // TAMBAHKAN INI agar tidak error
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();

        $query = Buku::with('kategori');

        // Filter Search
        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        // Filter Kategori
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->paginate(10);

        return view('buku.index', compact('buku', 'kategoris'));
    }

    public function create()
    {
        // Jangan lupa kirim kategoris ke view create agar dropdown bisa muncul
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'required|image',
            'kategori_id' => 'required' // Tambahkan validasi kategori
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $data['iduser'] = auth()->id();
        Buku::create($data); // Gunakan Buku (huruf kapital)

        return redirect()->route('buku.index')->with('success', 'Buku berhasil disimpan');
    }

    public function edit($id)
    {
        // Sesuaikan dengan primary key kamu 'idbuku'
        $buku = Buku::where('idbuku', $id)->firstOrFail();
        $kategoris = Kategori::all();

        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::where('idbuku', $id)->firstOrFail();

        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'kategori_id' => 'required'
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Data berhasil diupdate');
    }

    public function show($id)
    {
        $item = Buku::where('idbuku', $id)->firstOrFail();

        if (auth()->user()->role === 'anggota') {
            return view('pinjam_buku', compact('item'));
        }

        return view('buku.detail', compact('item'));
    }

    public function destroy($id)
    {
        // Gunakan idbuku jika itu primary key-nya
        Buku::where('idbuku', $id)->firstOrFail()->delete();
        return back()->with('success', 'Data dihapus');
    }
}
