<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Konfirmasi Pengembalian Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alert Error (Penting untuk menangkap SQLSTATE Error sebelumnya) --}}
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wider">Daftar Pengajuan Kembali</h3>
                </div>

                <div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 w-10">No</th> {{-- Tambah Kolom No --}}
                <th class="px-6 py-4">Data Peminjam</th>
                <th class="px-6 py-4">Informasi Buku</th>
                <th class="px-6 py-4 text-center">Jatuh Tempo</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Aksi Konfirmasi</th>
            </tr>
        </thead>

       <tbody class="divide-y divide-gray-200">
    @forelse ($pengembalian as $item)
    <tr class="hover:bg-gray-50 transition duration-150">
        {{-- NOMOR URUT --}}
        <td class="px-6 py-4 font-semibold text-gray-500">
            {{ ($pengembalian->currentPage() - 1) * $pengembalian->perPage() + $loop->iteration }}
        </td>

        {{-- DATA PEMINJAM --}}
        <td class="px-6 py-4">
            <div class="flex flex-col">
                <span class="font-bold text-gray-900 dark:text-gray-200">{{ $item->user->name ?? 'N/A' }}</span>
                <span class="text-[10px] text-gray-400">ID PINJAM: #{{ $item->idpinjam }}</span>
            </div>
        </td>

        {{-- INFORMASI BUKU & JUMLAH --}}
        <td class="px-6 py-4">
            <div class="flex flex-col">
                <span class="font-medium text-gray-900 dark:text-gray-200">{{ $item->buku->judul ?? 'Buku Dihapus' }}</span>
                {{-- Badge Jumlah Buku --}}
              <div class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $item->jumlah }} Buku
                                    </div>
            </div>
        </td>

        {{-- JATUH TEMPO --}}
        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200 text-center">
            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}
        </td>
        
        {{-- STATUS --}}
        <td class="px-6 py-4 text-center">
            @if($item->status == 'proses kembali')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                    Menunggu Konfirmasi
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Sudah Kembali
                </span>
            @endif
        </td>

        {{-- AKSI --}}
        <td class="px-6 py-4 text-center">
            @if($item->status == 'proses kembali')
                <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                    @csrf 
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow-sm text-xs font-bold transition transform active:scale-95">
                        TERIMA BUKU
                    </button>
                </form>
            @else
                <div class="flex flex-col items-center">
                    <span class="text-gray-400 text-[10px] italic">Diterima pada:</span>
                    <span class="text-gray-500 text-xs font-semibold">{{ $item->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            @endif
        </td>
    </tr>
    @empty
    {{-- Bagian empty tetap sama --}}
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