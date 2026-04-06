<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Transaksi Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                        Semua Daftar Transaksi
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4">Informasi Peminjam</th>
                                <th class="px-6 py-4">Buku yang Dipinjam</th>
                                <th class="px-6 py-4 text-center">Tgl Pinjam</th>
                                <th class="px-6 py-4 text-center">Batas Kembali</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($peminjaman as $pinjam)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $pinjam->user->name ?? 'User N/A' }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono">ID: #{{ $pinjam->idpinjam }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">
                                    {{ $pinjam->buku->judul ?? 'Buku Dihapus' }}
                                    <div class="text-xs text-indigo-600 font-semibold mt-1">{{ $pinjam->jumlah }} Buku</div>
                                </td>

                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusLower = strtolower($pinjam->status);
                                        $badgeClass = [
                                            'menunggu'       => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'dipinjam'       => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'proses kembali' => 'bg-purple-100 text-purple-700 border-purple-200 animate-pulse',
                                            'kembali'        => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'terlambat'      => 'bg-red-100 text-red-700 border-red-200',
                                        ][$statusLower] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                        {{ strtoupper($pinjam->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        {{-- 1. SETUJUI PINJAMAN (Jika status Menunggu) --}}
                                        @if($statusLower == 'menunggu')
                                        <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="Dipinjam">
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] uppercase font-bold py-1.5 px-3 rounded shadow transition">
                                                Setujui
                                            </button>
                                        </form>

                                        {{-- 2. TERIMA BUKU (Jika status Proses Kembali) --}}
                                        @elseif($statusLower == 'proses kembali')
                                        <form action="{{ route('pengembalian.store', $pinjam->idpinjam) }}" method="POST">
                                            @csrf
                                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] uppercase font-bold py-1.5 px-3 rounded shadow transition">
                                                Terima Buku
                                            </button>
                                        </form>

                                        {{-- 3. INFO SELESAI (Jika status Kembali) --}}
                                        @elseif($statusLower == 'kembali')
                                        <span class="text-[10px] text-gray-400 italic">No Action Needed</span>
                                        @endif

                                        {{-- Tombol Hapus (Hanya untuk data yang sudah selesai atau dibatalkan) --}}
                                        <form action="{{ route('peminjaman.destroy', $pinjam->idpinjam) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini secara permanen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 p-1" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">
                                    Belum ada data transaksi peminjaman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($peminjaman->hasPages())
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>