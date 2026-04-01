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