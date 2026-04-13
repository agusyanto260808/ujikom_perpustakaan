<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-black fw-bold">
    {{ __('Dashboard') }}
</x-nav-link>

@if(Auth::user()->role == 'petugas')
    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.index')" class="text-black fw-bold">
        {{ __('Buku') }}
    </x-nav-link>

    <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')" class="text-black fw-bold">
        {{ __('Peminjaman') }}
    </x-nav-link>

    <x-nav-link :href="route('pengembalian.index')" :active="request()->routeIs('pengembalian.index')" class="text-black fw-bold">
        {{ __('Pengembalian') }}
    </x-nav-link>
@endif

<x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')" class="text-black fw-bold">
    {{ __('Laporan') }}
</x-nav-link>
{{-- Mengizinkan Petugas ATAU Kepala Perpus melihat menu Kelola Akun --}}
@if(Auth::user()->role == 'kep_perpus' || Auth::user()->role == 'petugas')
    <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 fw-bold">
                    <div>Kelola Akun</div>
                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                {{-- Keduanya (Petugas & Kep Perpus) bisa mengelola Akun User/Anggota --}}
                <x-dropdown-link :href="route('kelola_akun.index', ['role' => 'anggota'])" class="fw-bold">
                    {{ __('Akun User') }}
                </x-dropdown-link>

                {{-- Khusus Kepala Perpus saja yang bisa mengelola Akun Petugas --}}
                @if(Auth::user()->role == 'kep_perpus')
                    <x-dropdown-link :href="route('kelola_akun.index', ['role' => 'petugas'])" class="fw-bold">
                        {{ __('Akun Petugas') }}
                    </x-dropdown-link>
                @endif
            </x-slot>
        </x-dropdown>
    </div>
@endif