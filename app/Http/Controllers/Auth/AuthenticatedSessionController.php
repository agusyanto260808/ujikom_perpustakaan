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
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang baru saja login
        $user = $request->user();

        // Logika pengalihan berdasarkan role
        if ($user->role === 'kep_perpus' || $user->role === 'petugas') {
            // Jika admin atau petugas, arahkan ke dashboard admin
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Jika anggota, arahkan ke halaman utama atau dashboard anggota
        // Pastikan kamu sudah punya route bernama 'welcome' atau 'dashboard_anggota'
        return redirect()->intended(route('dashboard', absolute: false));
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
