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
            
            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-emerald-200/20 border border-white dark:border-gray-700">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full opacity-50"></div>
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
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 dark:bg-amber-900/20 rounded-full opacity-50"></div>
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

            {{-- Table Section --}}
            <div class="bg-white dark:bg-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-10">
                    <div class="mb-10 text-center md:text-left">
                        <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">Antrean Pengembalian</h3>
                        <p class="text-sm text-gray-400 mt-1">Konfirmasi pembayaran denda dan kembalikan buku ke rak.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                    <th class="px-6 py-2">Peminjam</th>
                                    <th class="px-6 py-2">Judul Buku</th>
                                    <th class="px-6 py-2 text-center">Batas Waktu</th>
                                    <th class="px-6 py-2 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengembalian as $item)
                                @php
                                    $tgl_kembali = \Carbon\Carbon::parse($item->tgl_kembali);
                                    $terlambat = $tgl_kembali->diffInDays(now(), false);
                                    $denda_per_hari = 15000; 
                                    $total_denda = $terlambat > 0 ? $terlambat * $denda_per_hari : 0;
                                @endphp
                                <tr class="group">
                                    {{-- Hanya Nama Peminjam --}}
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-l-2xl border-y border-l border-gray-100 dark:border-gray-700 group-hover:bg-gray-100 transition-colors">
                                        <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->user->name }}
                                        </span>
                                    </td>

                                    {{-- Hanya Judul Buku --}}
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 border-y border-gray-100 dark:border-gray-700 group-hover:bg-gray-100 transition-colors">
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $item->buku->judul }}
                                        </span>
                                    </td>

                                    {{-- Status Waktu & Denda --}}
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 border-y border-gray-100 dark:border-gray-700 text-center group-hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center">
                                            <span class="px-3 py-1 {{ $terlambat > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} rounded-lg text-[11px] font-black uppercase">
                                                {{ $tgl_kembali->format('d M Y') }}
                                            </span>
                                            @if($terlambat > 0)
                                                <span class="text-[10px] text-rose-500 font-bold mt-1 tracking-tighter">
                                                    Denda: Rp {{ number_format($total_denda) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-r-2xl border-y border-r border-gray-100 dark:border-gray-700 text-right group-hover:bg-gray-100 transition-colors">
                                        <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST" 
                                              onsubmit="return confirm('{{ $terlambat > 0 ? 'Denda Rp ' . number_format($total_denda) . ' sudah dibayar oleh ' . $item->nama_peminjam . '?' : 'Selesaikan pengembalian buku?' }}')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition-all active:scale-95">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                Selesai
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold italic">
                                        Tidak ada buku yang sedang dipinjam.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                {{-- ... kode table sebelumnya ... --}}
                    </div> {{-- Akhir dari overflow-x-auto --}}

                    {{-- Tambahkan Pagination di sini --}}
                    @if($pengembalian->hasPages())
                        <div class="mt-8 p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 rounded-b-[2.5rem]">
                            {{ $pengembalian->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>