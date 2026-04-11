<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    /**
     * Tampilan untuk ADMIN (Table View)
     */
    public function index(Request $request)
    {
        $kategoris = Kategori::all();
        $query = Buku::with(['kategori']);

        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->paginate(10);

        return view('buku.index', compact('buku', 'kategoris'));
    }

    /**
     * Tampilan untuk USER/KATALOG (Grid/Card View)
     */
    public function katalog(Request $request)
    {
        $query = Buku::with(['kategori']);

        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%")
                ->orWhere('penulis', 'like', "%{$request->search}%");
        }

        $buku = $query->paginate(15);

        return view('katalog_buku', compact('buku'));
    }

    public function create()
    {
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
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'kategori_id' => 'required'
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $data['iduser'] = auth()->id();
        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil disimpan');
    }

    public function edit($id)
    {
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

    /**
     * PERBAIKAN DI SINI:
     * Menggunakan variabel $item agar sesuai dengan file Blade Anda
     */
    public function show($id)
    {
        // Ambil data dengan nama variabel $item
        $item = Buku::with('kategori')->where('idbuku', $id)->firstOrFail();

        if (auth()->user()->role === 'anggota') {
            // Kirim 'item' ke view pinjam_buku
            return view('pinjam_buku', compact('item'));
        }

        // Kirim 'item' ke view detail admin
        return view('buku.detail', compact('item'));
    }

    public function destroy($id)
    {
        Buku::where('idbuku', $id)->firstOrFail()->delete();
        return back()->with('success', 'Data dihapus');
    }
}
