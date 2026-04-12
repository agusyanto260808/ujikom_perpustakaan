<x-nav-link :href="route('dashboard_user.index')" :active="request()->routeIs('dashboard_user.index')" class="text-black fw-bold">
    {{ __('Dashboard') }}
</x-nav-link>

<x-nav-link :href="route('katalog_buku.index')" :active="request()->routeIs('katalog_buku.index')" class="text-black fw-bold">
    {{ __('Katalog Buku') }}
</x-nav-link>

{{-- Tambahkan menu lain khusus anggota di sini nanti --}}
<x-nav-link :href="route('riwayat_peminjaman.index')" :active="request()->routeIs('riwayat_peminjaman.index')" class="text-black fw-bold">
    {{ __('Riwayat Peminjaman') }}
</x-nav-link>


