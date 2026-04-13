<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class KelolaAkunController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna
     */
    public function index(Request $request)
    {
        $role = $request->get('role', 'anggota');

        // PROTEKSI: Petugas hanya boleh melihat daftar 'anggota'
        if (Auth::user()->role == 'petugas' && $role !== 'anggota') {
            return redirect()->route('kelola_akun.index', ['role' => 'anggota']);
        }

        $users = User::where('role', $role)
            ->where('role', '!=', 'admin') // Admin tidak muncul di daftar mana pun
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kelola_akun.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('kelola_akun.create');
    }

    /**
     * Menyimpan data akun baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:petugas,anggota', // Admin tidak boleh dibuat lewat sini
            'nisn' => 'nullable|required_if:role,anggota|unique:users,nisn',
        ]);

        $role = $request->role;

        // PROTEKSI: Jika petugas yang buat, paksa role jadi 'anggota'
        if (Auth::user()->role == 'petugas') {
            $role = 'anggota';
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'nisn' => ($role === 'anggota') ? $request->nisn : null,
        ]);

        return redirect()->route('kelola_akun.index', ['role' => $role])->with('success', 'Akun berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // PROTEKSI: Petugas tidak boleh edit akun sesama Petugas atau Kepala
        if (Auth::user()->role == 'petugas' && $user->role !== 'anggota') {
            return redirect()->route('kelola_akun.index')->with('error', 'Anda tidak memiliki akses edit akun ini.');
        }

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
            'role' => 'required|in:petugas,anggota',
            'nisn' => 'nullable|required_if:role,anggota|unique:users,nisn,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $request->role;

        // PROTEKSI: Petugas tidak boleh mengubah role ke selain 'anggota'
        if (Auth::user()->role == 'petugas') {
            $role = 'anggota';
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'nisn' => ($role === 'anggota') ? $request->nisn : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('kelola_akun.index', ['role' => $role])->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // PROTEKSI: Petugas tidak boleh hapus akun selain 'anggota'
        if (Auth::user()->role == 'petugas' && $user->role !== 'anggota') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }
}
