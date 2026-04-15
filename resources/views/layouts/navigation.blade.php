<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-[100] bg-white border-b border-gray-200 shadow-md w-full h-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-black-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->role === 'anggota')
                        @include('layouts.nav-user')
                    @else
                        @include('layouts.nav-admin')
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                {{-- LONCENG: Hanya muncul jika role = anggota --}}
                @if(Auth::user()->role === 'anggota')
                    <div class="dropdown me-3">
                        <button class="btn btn-link position-relative p-0 border-0 shadow-none align-middle" 
                                type="button" 
                                id="notificationBell"
                                data-bs-toggle="dropdown" 
                                aria-expanded="false" 
                                style="color: #4158D0;">
                            
                            <i class="bi bi-bell-fill fs-5"></i>
                            
                            @php
                                $unreadCount = \App\Models\Peminjaman::where('iduser', auth()->id())
                                                ->whereIn('status', ['Dipinjam', 'Ditolak', 'Kembali'])
                                                ->where('is_read', 0)
                                                ->count();
                            @endphp
                            
                            @if($unreadCount > 0)
                                <span id="unread-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.55rem; padding: 0.35em 0.5em;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 320px; border-radius: 12px;">
                            <li class="px-3 py-2 fw-bold border-bottom mb-2 text-sm d-flex justify-between align-items-center">
                                <span>Notifikasi</span>
                                <span class="badge bg-primary-subtle text-primary" style="font-size: 0.6rem;">24 Jam Terakhir</span>
                            </li>
                            
                            @php
                                $recentNotifs = \App\Models\Peminjaman::where('iduser', auth()->id())
                                                ->whereIn('status', ['Dipinjam', 'Ditolak', 'Kembali'])
                                                ->where('updated_at', '>=', now()->subDay())
                                                ->latest('updated_at')
                                                ->take(5)
                                                ->get();
                            @endphp

                            @forelse($recentNotifs as $notif)
                                <li class="mb-1">
                                    <a class="dropdown-item p-2 rounded {{ $notif->is_read == 0 ? 'bg-light' : '' }}" href="{{ route('riwayat_peminjaman.index') }}" style="white-space: normal;">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-center">
                                                @php
                                                    $statusClass = $notif->status == 'Ditolak' ? 'text-danger' : ($notif->status == 'Kembali' ? 'text-success' : 'text-primary');
                                                @endphp
                                                <span class="fw-bold text-xs {{ $statusClass }}">
                                                    {{ $notif->status == 'Dipinjam' ? 'Disetujui' : ($notif->status == 'Kembali' ? 'Selesai' : 'Ditolak') }}
                                                </span>
                                                <small class="text-gray-400" style="font-size: 0.6rem;">{{ $notif->updated_at->diffForHumans() }}</small>
                                            </div>
                                            <span class="text-dark fw-medium" style="font-size: 0.75rem;">Buku: {{ $notif->buku->judul }}</span>
                                            
                                            @if($notif->status == 'Ditolak')
                                                <div class="mt-1 p-2 bg-danger-subtle rounded border-start border-danger border-3">
                                                    <small class="text-danger d-block" style="font-size: 0.65rem; line-height: 1.2;">
                                                        <strong>Alasan:</strong> {{ $notif->pesan ?? 'Tidak ada keterangan tambahan' }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="text-center py-4 text-muted small">
                                    Tidak ada aktivitas terbaru
                                </li>
                            @endforelse
                        </ul>
                    </div>
                @endif

                {{-- DROPDOWN PROFIL --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="fw-bold">{{ Auth::user()->role }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-black hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>