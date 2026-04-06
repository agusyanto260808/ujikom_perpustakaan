<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                        Daftar Transaksi Peminjaman
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
    <tr>
        <th class="px-6 py-4">Nama Peminjam</th>
        <th class="px-6 py-4">Buku yang Dipinjam</th>
        <th class="px-6 py-4 text-center">Tgl Pinjam</th>
        <th class="px-6 py-4 text-center">Tgl Kembali</th>
        <th class="px-6 py-4 text-center">Denda Terlambat</th> {{-- Kolom Baru --}}
        <th class="px-6 py-4 text-center">Status</th>
    </tr>
</thead>
                       <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @forelse ($peminjaman as $pinjam)
        @php
            $status = strtolower($pinjam->status);
            $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);
            $hariIni = now();
            $tarifDenda = 1000;
            $totalDenda = 0;
            $selisihHari = 0;

            // Hitung denda jika terlambat dan belum 'kembali' sepenuhnya
            if ($hariIni->gt($jatuhTempo) && ($status == 'dipinjam' || $status == 'proses kembali')) {
                $selisihHari = $hariIni->diffInDays($jatuhTempo);
                $totalDenda = $selisihHari * $tarifDenda;
            }
        @endphp

        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                {{ $pinjam->user->name ?? 'User' }}
            </td>
            <td class="px-6 py-4">
                <div class="text-white font-medium">{{ $pinjam->buku->judul }}</div>
                <div class="text-xs text-blue-400">{{ $pinjam->jumlah }} Buku</div>
            </td>
            <td class="px-6 py-4 text-center text-gray-400">
                {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}
            </td>
            <td class="px-6 py-4 text-center text-gray-400">
                {{ $jatuhTempo->format('d/m/Y') }}
            </td>

            {{-- KOLOM DENDA --}}
            <td class="px-6 py-4 text-center">
                @if($totalDenda > 0)
                    <span class="text-red-500 font-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                    <p class="text-[10px] text-red-400">Terlambat {{ $selisihHari }} hari</p>
                @elseif($status == 'kembali')
                    <span class="text-gray-500">Lunas / Tepat Waktu</span>
                @else
                    <span class="text-emerald-500">Tidak ada denda</span>
                @endif
            </td>

            {{-- KOLOM STATUS --}}
            <td class="px-6 py-4 text-center">
                @if($status == 'dipinjam')
                    <form action="{{ route('pengembalian.ajukan', $pinjam->idpinjam) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold"
                            onclick="return confirm('{{ $totalDenda > 0 ? 'Denda Anda Rp ' . number_format($totalDenda, 0, ',', '.') . '. Lanjutkan?' : 'Ajukan pengembalian?' }}')">
                            AJUKAN KEMBALI
                        </button>
                    </form>
                @elseif($status == 'proses kembali')
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-[10px] font-bold">MENUNGGU KONFIRMASI</span>
                @elseif($status == 'kembali')
                    <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-[10px] font-bold">SELESAI</span>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center py-4 text-gray-500">Belum ada riwayat.</td></tr>
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