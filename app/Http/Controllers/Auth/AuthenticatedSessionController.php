<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang baru saja login
        $user = $request->user();

        // Logika pesan sukses berdasarkan nama user
        $pesan = 'Selamat Datang Kembali, ' . $user->name . '!';

        // Logika pengalihan berdasarkan role dengan tambahan session flash 'success'
        if ($user->role === 'kep_perpus' || $user->role === 'petugas') {
            return redirect()->intended(route('dashboard', absolute: false))
                ->with('success', $pesan);
        }

        // Untuk anggota atau role lainnya
        return redirect()->intended(route('dashboard_user.index', absolute: false))
            ->with('success', $pesan);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
