<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
           <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                📚 Daftar Buku
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Total Buku: <span class="font-semibold">{{ $buku->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-800 border-l-4 border-green-500 text-green-700 dark:text-green-100 p-4 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-800 border-l-4 border-red-500 text-red-700 dark:text-red-100 p-4 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search & Filter Bar -->
            <div class="mb-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-4">
                <form method="GET" action="{{ route('buku.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari judul, penulis, atau penerbit..." 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition duration-200">
                            🔍 Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('buku.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition duration-200">
                                ↻ Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">

                <!-- Header Action -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-white dark:from-gray-700 dark:to-gray-800">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            📖 Data Buku
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Kelola koleksi buku perpustakaan Anda
                        </p>
                    </div>

                    <a href="{{ route('buku.create') }}"
                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2.5 rounded-lg shadow-lg transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Buku Baru
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 font-medium">ID</th>
                                <th class="px-6 py-4 font-medium">Sampul</th>
                                <th class="px-6 py-4 font-medium">Judul Buku</th>
                                <th class="px-6 py-4 font-medium">Penulis</th>
                                <th class="px-6 py-4 font-medium">Penerbit</th>
                                <th class="px-6 py-4 font-medium text-center">Tahun</th>
                                <th class="px-6 py-4 font-medium text-center">Stok</th>
                                <th class="px-6 py-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($buku as $item)
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                <!-- Hapus hover:bg-gray-50 dan dark:hover:bg-gray-700/50 -->
                                
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $item->idbuku }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                             alt="{{ $item->judul }}"
                                             class="w-10 h-14 object-cover rounded shadow-md">
                                        <!-- Ukuran diubah dari w-16 h-24 menjadi w-10 h-14 -->
                                    @else
                                        <div class="w-10 h-14 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center shadow-inner">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">No Cover</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-1">
                                        {{ $item->judul }}
                                    </div>
                                    @if($item->isbn)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            ISBN: {{ $item->isbn }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">

                                        {{ $item->penulis }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        {{ $item->penerbit }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                         {{ $item->tahun }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $stokClass = $item->stok <= 0 ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' : 
                                                     ($item->stok <= 5 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                     'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200');
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $stokClass }}">
                                        @if($item->stok <= 0)
                                             Habis
                                        @elseif($item->stok <= 5)
                                            {{ $item->stok }}
                                        @else
                                             {{ $item->stok }}
                                        @endif
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <!-- Detail View -->
                                        {{-- <a href="{{ route('buku.show', $item->idbuku) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg transition-colors duration-200"
                                           title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a> --}}

                                        <!-- Edit -->
                                        <a href="{{ route('buku.edit', $item->idbuku) }}"
                                           class="p-2 text-yellow-600 hover:bg-yellow-100 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-lg transition-colors duration-200"
                                           title="Edit Buku">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <button type="button"
                                                onclick="confirmDelete({{ $item->idbuku }}, '{{ $item->judul }}')"
                                                class="p-2 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition-colors duration-200"
                                                title="Hapus Buku">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>

                                        <!-- Delete Form (Hidden) -->
                                        <form id="delete-form-{{ $item->idbuku }}" 
                                              action="{{ route('buku.destroy', $item->idbuku) }}" 
                                              method="POST" 
                                              class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-16">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <p class="text-lg font-medium">Belum ada data buku</p>
                                        <p class="text-sm mt-1">Silakan tambah buku baru dengan klik tombol di atas</p>
                                        <a href="{{ route('buku.create') }}" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                            + Tambah Buku Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($buku->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $buku->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function confirmDelete(id, judul) {
    // Cek apakah SweetAlert2 tersedia
    if (typeof Swal !== 'undefined' && Swal) {
        Swal.fire({
            title: 'Hapus Buku?',
            html: `Apakah Anda yakin ingin menghapus buku <strong>"${judul}"</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    } else {
        // Fallback ke confirm biasa
        if (confirm(`Apakah Anda yakin ingin menghapus buku "${judul}"?`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
}
</script>


<style>
    /* Menghilangkan efek highlight saat klik pada tabel */
    table {
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    
    /* Menghilangkan outline pada tabel saat di klik */
    table:focus,
    tr:focus,
    td:focus {
        outline: none;
    }
    
    /* Menghilangkan efek background saat row di klik (opsional) */
    tr:active {
        background-color: transparent !important;
    }
    
    /* Memastikan hover tetap tidak ada efek (jika sebelumnya dihapus) */
    tbody tr {
        transition: none;
    }
    
    /* Gambar tidak memiliki efek hover scale */
    img {
        transition: none;
    }
    
    img:hover {
        transform: none;
    }
</style>