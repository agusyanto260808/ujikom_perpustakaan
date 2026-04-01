<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Laporan') }} <span class="text-rose-600">Denda</span>
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Rekapitulasi sanksi keterlambatan pengembalian buku.</p>
            </div>
            <button onclick="window.print()" class="hidden md:flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-rose-200/20 border border-white dark:border-gray-700">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase text-rose-400 tracking-[0.2em] mb-1">Total Kas Denda</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white leading-none">
                                <span class="text-lg font-bold text-rose-500 mr-1">Rp</span>{{ number_format($peminjamans->sum('denda'), 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>
                
                <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-amber-200/20 border border-white dark:border-gray-700">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase text-amber-500 tracking-[0.2em] mb-1">Kasus Terlambat</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white leading-none">
                                {{ $peminjamans->where('denda', '>', 0)->count() }} <span class="text-sm font-bold text-gray-400">Data</span>
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
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">Riwayat Pembayaran</h3>
                            <p class="text-sm text-gray-400 mt-1">Menampilkan data denda yang telah diselesaikan.</p>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" placeholder="Cari peminjam..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-rose-500 transition-all w-full md:w-64">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                    <th class="px-6 py-4">Peminjam</th>
                                    <th class="px-6 py-4">Detail Buku</th>
                                    <th class="px-6 py-4 text-center">Batas Kembali</th>
                                    <th class="px-6 py-4 text-center">Nominal Denda</th>
                                    <th class="px-6 py-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-8 divide-transparent">
                                @forelse ($peminjamans->where('denda', '>', 0) as $item)
                                <tr class="group bg-white dark:bg-gray-800/50 hover:shadow-xl hover:shadow-gray-200/40 dark:hover:shadow-none transition-all duration-300">
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-l-[1.5rem] border-y border-l border-gray-50 dark:border-gray-700">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center font-black text-xs mr-4">
                                                {{ strtoupper(substr($item->nama_peminjam, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 dark:text-white leading-none">{{ $item->nama_peminjam }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold mt-1 tracking-tighter">ID: TX-{{ $item->idpeminjaman }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 border-y border-gray-50 dark:border-gray-700">
                                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $item->buku->judul }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center border-y border-gray-50 dark:border-gray-700">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-gray-300 uppercase">
                                            {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-center border-y border-gray-50 dark:border-gray-700">
                                        <span class="text-sm font-black text-rose-600 bg-rose-50 px-4 py-2 rounded-xl">
                                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-right bg-gray-50/50 dark:bg-gray-700/30 rounded-r-[1.5rem] border-y border-r border-gray-50 dark:border-gray-700">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500 text-white shadow-lg shadow-emerald-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Lunas
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-32 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-lg font-black text-gray-300 tracking-tight">Data Tidak Ditemukan</p>
                                            <p class="text-sm text-gray-400 italic">Belum ada catatan denda yang tersimpan.</p>
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