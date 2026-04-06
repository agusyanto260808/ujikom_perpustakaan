<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class KelolaAkunController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('kelola_akun.index', compact('users'));
    }

    /**
     * Menampilkan form pendaftaran akun baru (HANYA VIEW)
     */
    public function create()
    {
        return view('kelola_akun.create');
    }

    /**
     * Menyimpan data akun baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,petugas,siswa',
            'nisn' => 'nullable|required_if:role,siswa|unique:users,nisn',
        ]);

        // 2. Proses Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Logika: Jika role siswa simpan NISN, jika tidak simpan null
            'nisn' => ($request->role === 'siswa') ? $request->nisn : null,
        ]);

        // 3. Redirect ke Halaman Index
        return redirect()->route('kelola_akun.index')->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Menghapus akun pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('kelola_akun.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('kelola_akun.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,petugas,siswa',
            'nisn' => 'nullable|required_if:role,siswa|unique:users,nisn,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'nisn' => ($request->role === 'siswa') ? $request->nisn : null,
        ]);

        return redirect()->route('kelola_akun.index')->with('success', 'Data diperbarui!');
    }
}
