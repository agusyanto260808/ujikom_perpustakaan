<x-nav-link :href="route('katalog_buku.index')" :active="request()->routeIs('katalog_buku.index')">
    {{ __('Katalog Buku') }}
</x-nav-link>

{{-- Tambahkan menu lain khusus anggota di sini nanti --}}
<x-nav-link :href="route('riwayat_peminjaman.index')" :active="request()->routeIs('riwayat_peminjaman.index')">
    {{ __('Pinjaman Saya') }}
</x-nav-link>
<x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
    {{ __('Profil Saya') }}
</x-nav-link>