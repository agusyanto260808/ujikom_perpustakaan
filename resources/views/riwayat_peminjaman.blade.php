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
    $status = trim(strtolower($pinjam->status));
    
    $tglPinjam = \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->startOfDay();
    $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();
    
    // 1. Ambil tanggal realita pengembalian (jika ada di tabel pengembalian)
    $tglKembaliData = $pinjam->pengembalian ? $pinjam->pengembalian->tanggalkembali : $pinjam->tanggal_kembali;

    // 2. LOGIKA PATOKAN: 
    // Jika status sudah 'kembali/selesai/lunas', patokannya adalah tanggal dia mengembalikan buku.
    // Jika status MASIH 'dipinjam' atau 'proses', patokannya adalah HARI INI (now).
    if (in_array($status, ['kembali', 'selesai', 'lunas']) && $tglKembaliData) {
        $tanggalPatokan = \Carbon\Carbon::parse($tglKembaliData)->startOfDay();
    } else {
        $tanggalPatokan = \Carbon\Carbon::now()->startOfDay();
    }
    
    $tarifDenda = 2000; 
    $totalDenda = 0;
    $selisihHari = 0;

    // 3. HITUNG DENDA:
    if ($tanggalPatokan->gt($jatuhTempo)) {
        $selisihHari = $tanggalPatokan->diffInDays($jatuhTempo);
        $totalDenda = $selisihHari * $tarifDenda;
    }
    
@endphp
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 border-b border-gray-100 dark:border-gray-700">
        {{-- NAMA PEMINJAM --}}
        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
            {{ $pinjam->user->name ?? 'User' }}
        </td>

        {{-- INFO BUKU --}}
        <td class="px-6 py-4">
            <div class="text-gray-800 dark:text-gray-200 font-semibold">{{ $pinjam->buku->judul }}</div>
            <div class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                {{ $pinjam->jumlah }} Buku
            </div>
        </td>

        {{-- TANGGAL --}}
        <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}
</td>
       <td class="px-6 py-4 text-center {{ $totalDenda > 0 ? 'text-red-600 font-bold' : 'text-gray-600 dark:text-gray-400' }}">
    {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
</td>

<td class="px-6 py-4 text-center">
    @if($totalDenda > 0)
        <span class="text-green-500 font-bold text-[11px]">Aman</span>
    @elseif(in_array($status, ['kembali', 'selesai', 'lunas']))
        <span class="inline-flex items-center text-emerald-600 font-bold text-xs uppercase">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            Lunas
        </span>
    @else
        
        <div class="flex flex-col items-center">
            <span class="text-red-600 font-extrabold text-sm">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
            <span class="text-[9px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-bold mt-1 uppercase tracking-tighter">
                Telat {{ $selisihHari }} Hari
            </span>
        </div>
    @endif
</td>

        {{-- STATUS & ACTION --}}
        <td class="px-6 py-4 text-center">
            @if($status == 'dipinjam')
                @if($totalDenda > 0)
                    {{-- Tombol Terkunci jika ada denda --}}
                    <button type="button" 
                        class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 cursor-not-allowed px-3 py-2 rounded text-[10px] font-bold border border-gray-300"
                        onclick="alert('Harap lunasi denda Rp {{ number_format($totalDenda, 0, ',', '.') }} di petugas!')">
                        BAYAR & KEMBALIKAN
                    </button>
                @else
                    <form action="{{ route('pengembalian.ajukan', $pinjam->idpinjam) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-[10px] font-bold shadow-sm transition transform active:scale-95"
                            onclick="return confirm('Ajukan pengembalian sekarang?')">
                            AJUKAN KEMBALI
                        </button>
                    </form>
                @endif
            @elseif($status == 'proses kembali')
                <span class="inline-block w-full py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold animate-pulse uppercase">
                    Dicek Petugas
                </span>
            @elseif($status == 'kembali')
                <span class="inline-block w-full py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold uppercase">
                    Selesai
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-12 text-gray-400 italic">Belum ada riwayat transaksi.</td>
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