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
        // Ubah default ke 'anggota'
        $role = $request->get('role', 'anggota');

        $users = \App\Models\User::where('role', $role)
            ->where('role', '!=', 'admin')
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Ganti 'siswa' menjadi 'anggota'
            'role' => 'required|in:admin,petugas,anggota',
            'nisn' => 'nullable|required_if:role,anggota|unique:users,nisn',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Ganti pengecekan 'siswa' menjadi 'anggota'
            'nisn' => ($request->role === 'anggota') ? $request->nisn : null,
        ]);

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
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Ganti 'siswa' menjadi 'anggota'
            'role' => 'required|in:admin,petugas,anggota',
            'nisn' => 'nullable|required_if:role,anggota|unique:users,nisn,' . $user->id,
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            // Ganti pengecekan 'siswa' menjadi 'anggota'
            'nisn' => ($request->role === 'anggota') ? $request->nisn : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('kelola_akun.index')->with('success', 'Akun ' . $user->name . ' berhasil diperbarui!');
    }
}
