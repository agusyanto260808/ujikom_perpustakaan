<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Proses') }} <span class="text-emerald-600">Pengembalian</span>
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Kelola buku yang sedang dipinjam dan selesaikan transaksi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-emerald-200/20 border border-white dark:border-gray-700">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase text-emerald-400 tracking-[0.2em] mb-1">Buku Dipinjam</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white leading-none">
                                {{ $pengembalian->count() }} <span class="text-sm font-bold text-gray-400">Total</span>
                            </h3>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-amber-200/20 border border-white dark:border-gray-700">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase text-amber-500 tracking-[0.2em] mb-1">Lewat Jatuh Tempo</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white leading-none">
                                {{ $pengembalian->filter(fn($i) => \Carbon\Carbon::parse($i->tgl_kembali)->isPast())->count() }} <span class="text-sm font-bold text-gray-400">Kasus</span>
                            </h3>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-10">
                    <div class="mb-10">
                        <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">Antrean Pengembalian</h3>
                        <p class="text-sm text-gray-400 mt-1">Daftar buku yang harus segera dikembalikan ke rak.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                    <th class="px-6 py-4">Peminjam</th>
                                    <th class="px-6 py-4">Informasi Buku</th>
                                    <th class="px-6 py-4 text-center">Batas Waktu</th>
                                    <th class="px-6 py-4 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-8 divide-transparent">
                                @forelse ($pengembalian as $item)
                                <tr class="group bg-white dark:bg-gray-800/50 hover:shadow-xl hover:shadow-gray-200/40 dark:hover:shadow-none transition-all duration-300">
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-l-[1.5rem] border-y border-l border-gray-50 dark:border-gray-700">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-black text-xs mr-4 shadow-sm">
                                                {{ strtoupper(substr($item->nama_peminjam, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 dark:text-white leading-none">{{ $item->nama_peminjam }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold mt-1 tracking-tighter">ID: TX-{{ $item->idpinjam }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 border-y border-gray-50 dark:border-gray-700">
                                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 leading-tight">
                                            {{ $item->buku->judul }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase font-bold">ISBN: {{ $item->buku->isbn ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-6 text-center border-y border-gray-50 dark:border-gray-700">
                                        <div class="inline-flex flex-col">
                                            <span class="px-3 py-1 {{ \Carbon\Carbon::parse($item->tgl_kembali)->isPast() ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-600' }} dark:bg-gray-700 rounded-lg text-[11px] font-bold uppercase">
                                                {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}
                                            </span>
                                            @if(\Carbon\Carbon::parse($item->tgl_kembali)->isPast())
                                                <span class="text-[9px] text-rose-500 font-black uppercase mt-1 animate-pulse tracking-tighter">Terlambat!</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-right bg-gray-50/50 dark:bg-gray-700/30 rounded-r-[1.5rem] border-y border-r border-gray-50 dark:border-gray-700">
                                       <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-emerald-100 dark:shadow-none">
                                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                Selesaikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-32 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="text-lg font-black text-gray-300 tracking-tight">Semua Buku Sudah Kembali</p>
                                            <p class="text-sm text-gray-400 italic">Tidak ada transaksi peminjaman aktif saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        tbody tr {
            animation: fadeIn 0.4s ease forwards;
        }
    </style>
</x-app-layout>