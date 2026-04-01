<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proses Pengembalian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Menunggu Pengembalian</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Peminjam</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Judul Buku</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Batas Waktu</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($pengembalian as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $item->nama_peminjam }}</div>
                                    <div class="text-xs text-gray-400">Peminjam Aktif</div>
                                </td>

                                <td class="px-6 py-4 font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ $item->buku->judul }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="block font-medium text-gray-700 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}
                                    </span>
                                    @if(\Carbon\Carbon::parse($item->tgl_kembali)->isPast())
                                        <span class="text-[10px] text-red-600 font-bold uppercase">Terlambat!</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('pengembalian.proses', $item->idpeminjaman) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition duration-150 shadow-sm">
                                            Selesaikan
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                    Tidak ada peminjaman aktif yang perlu diproses.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pengembalian->hasPages())
                    <div class="mt-6">
                        {{ $pengembalian->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>