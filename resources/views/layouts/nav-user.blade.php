<x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
    {{ __('Katalog Buku') }}
</x-nav-link>

{{-- Tambahkan menu lain khusus anggota di sini nanti --}}
<x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
    {{ __('Pinjaman Saya') }}
</x-nav-link>