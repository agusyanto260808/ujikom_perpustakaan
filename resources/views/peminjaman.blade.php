<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Log Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg flex items-center animate-fade-in-down">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold uppercase tracking-wider text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-2xl shadow-gray-200/60 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">Transaksi Peminjaman</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau sirkulasi buku dan tenggat waktu pengembalian secara efisien.</p>
                        </div>
    
                    </div>

                  <div class="overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-6 py-4">Peminjam</th>
                <th class="px-6 py-4 text-center">Tgl Pinjam</th>
                <th class="px-6 py-4 text-center">Batas Kembali</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y dark:divide-gray-700">
            @forelse ($peminjamans as $pinjam)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                
                <!-- Nama -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-full bg-indigo-500 text-white font-bold text-sm">
                            {{ substr($pinjam->nama_peminjam, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-white">
                                {{ $pinjam->nama_peminjam }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $pinjam->buku->judul }}
                            </div>
                        </div>
                    </div>
                </td>

                <!-- Tanggal -->
                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">
                    {{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d M Y') }}
                </td>

                <td class="px-6 py-4 text-center font-medium text-gray-700 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y') }}
                </td>

                <!-- Status -->
                <td class="px-6 py-4 text-center">
                    @if($pinjam->status == 'Dipinjam')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                            Dipinjam
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Kembali
                        </span>
                    @endif
                </td>

                <!-- Aksi -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        
                        @if($pinjam->status == 'Dipinjam')
                        <form action="{{ route('peminjaman.update', $pinjam->idpeminjaman) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 text-xs bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                                Kembalikan
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('peminjaman.destroy', $pinjam->idpeminjaman) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600">
                                Hapus
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-16 text-gray-400">
                    Belum ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

                    @if($peminjamans->hasPages())
                        <div class="mt-8">
                            {{ $peminjamans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>