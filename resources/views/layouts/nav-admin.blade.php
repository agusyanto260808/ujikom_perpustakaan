<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-nav-link>

<x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.index')">
    {{ __('Buku') }}
</x-nav-link>

<x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
    {{ __('Peminjaman') }}
</x-nav-link>

<x-nav-link :href="route('pengembalian.index')" :active="request()->routeIs('pengembalian.index')">
    {{ __('Pengembalian') }}
</x-nav-link>

<x-nav-link :href="route('denda.index')" :active="request()->routeIs('denda.index')">
    {{ __('Denda') }}
</x-nav-link>

<div class="hidden sm:flex sm:items-center sm:ms-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>Kelola Akun</div>

                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
    {{-- Link untuk Akun User/Siswa --}}
    <x-dropdown-link :href="route('kelola_akun.index', ['role' => 'anggota'])">
        {{ __('Akun User') }}
    </x-dropdown-link>

    {{-- Link untuk Akun Petugas --}}
    <x-dropdown-link :href="route('kelola_akun.index', ['role' => 'petugas'])">
        {{ __('Akun Petugas') }}
    </x-dropdown-link>
</x-slot>
    </x-dropdown>
</div>

