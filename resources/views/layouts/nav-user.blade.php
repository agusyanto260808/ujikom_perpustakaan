<x-nav-link :href="route('katalog_buku.index')" :active="request()->routeIs('katalog_buku.index')">
    {{ __('Katalog Buku') }}
</x-nav-link>

{{-- Tambahkan menu lain khusus anggota di sini nanti --}}
<x-nav-link :href="route('riwayat_peminjaman.index')" :active="request()->routeIs('riwayat_peminjaman.index')">
    {{ __('Riwayat Peminjaman') }}
</x-nav-link>

