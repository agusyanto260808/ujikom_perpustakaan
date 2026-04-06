<x-nav-link :href="route('katalog_buku.index')" :active="request()->routeIs('katalog_buku.index')">
    {{ __('Katalog Buku') }}
</x-nav-link>

{{-- Tambahkan menu lain khusus anggota di sini nanti --}}
<x-nav-link :href="route('riwayat_peminjaman.index')" :active="request()->routeIs('riwayat_peminjaman.index')">
    {{ __('Riwayat Peminjaman') }}
</x-nav-link>
<x-nav-link :href="route('kembali_buku.index')" :active="request()->routeIs('kembali_buku.index')">
    {{ __('Pengembalian Buku') }}
</x-nav-link>
