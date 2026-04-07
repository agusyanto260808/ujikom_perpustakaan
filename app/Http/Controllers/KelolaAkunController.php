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
    public function index(Request $request)
    {
        // Ambil parameter role dari URL, default ke 'siswa'
        $role = $request->get('role', 'siswa');

        // Filter user berdasarkan role yang dipilih
        $users = \App\Models\User::where('role', $role)
            ->where('role', '!=', 'admin') // Opsional: Sembunyikan admin dari list
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Penting: Agar filter role tidak hilang saat pindah page

        return view('kelola_akun.index', compact('users', 'role'));
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

        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,siswa',
            'nisn' => 'nullable|required_if:role,siswa|unique:users,nisn,' . $user->id,
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 2. Data Dasar
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nisn' => ($request->role === 'siswa') ? $request->nisn : null,
        ];

        // 3. Logika Password (Hanya update jika diisi)
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('kelola_akun.index')->with('success', 'Akun ' . $user->name . ' berhasil diperbarui!');
    }
}
