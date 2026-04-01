<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index()
    {

        $buku = Buku::all();

        // Points to resources/views/buku/index.blade.php
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
    public function destroy($id)
    {
        buku::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus');
    }
}
