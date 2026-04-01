<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Proses Pengembalian') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-500 text-white rounded-2xl shadow-lg flex items-center animate-bounce">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-2xl shadow-gray-200/60 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    
                    <div class="mb-10">
                        <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">Menunggu Pengembalian</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar di bawah adalah buku yang statusnya masih <b>"Dipinjam"</b>. Klik centang untuk mengembalikan.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr>
                                    <th class="px-6 py-5 bg-gray-50/80 dark:bg-gray-700/50 text-[11px] font-black uppercase tracking-[0.1em] text-gray-400 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 rounded-tl-2xl">Data Peminjam</th>
                                    <th class="px-6 py-5 bg-gray-50/80 dark:bg-gray-700/50 text-[11px] font-black uppercase tracking-[0.1em] text-gray-400 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">Judul Buku</th>
                                    <th class="px-6 py-5 bg-gray-50/80 dark:bg-gray-700/50 text-[11px] font-black uppercase tracking-[0.1em] text-gray-400 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 text-center">Batas Waktu</th>
                                    <th class="px-6 py-5 bg-gray-50/80 dark:bg-gray-700/50 text-[11px] font-black uppercase tracking-[0.1em] text-gray-400 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 text-center rounded-tr-2xl">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse ($pengembalian as $item)
                                <tr class="group hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-all duration-200">
                                    <td class="px-6 py-6">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400 mr-4 shadow-inner">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="text-base font-black text-gray-900 dark:text-white">{{ $item->nama_peminjam }}</div>
                                                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Peminjam Aktif</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $item->buku->judul }}
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5 italic">ID Buku: #{{ $item->idbuku }}</div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="inline-flex flex-col">
                                            <span class="text-xs font-black text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}</span>
                                            @if(\Carbon\Carbon::parse($item->tgl_kembali)->isPast())
                                                <span class="text-[9px] text-rose-500 font-bold uppercase animate-pulse">Terlambat!</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <form action="{{ route('pengembalian.proses', $item->idpeminjaman) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-5 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-100 dark:shadow-none hover:scale-105 active:scale-95 group">
                                                <svg class="w-4 h-4 mr-2 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                Selesaikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-32 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="text-xl font-bold text-gray-400 tracking-tight">Semua Buku Sudah Kembali</p>
                                            <p class="text-sm text-gray-400 mt-1 italic">Tidak ada peminjaman aktif yang perlu diproses.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pengembalian->hasPages())
                        <div class="mt-8 border-t border-gray-50 dark:border-gray-700 pt-6">
                            {{ $pengembalian->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>