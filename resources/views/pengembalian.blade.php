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
                                <th class="px-6 py-4">Data Peminjam</th>
                                <th class="px-6 py-4">Informasi Buku</th>
                                <th class="px-6 py-4 text-center">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-center">Aksi Konfirmasi</th>
                            </tr>
                        </thead>
                       {{-- resources/views/pengembalian.blade.php --}}

{{-- resources/views/pengembalian.blade.php --}}

<tbody class="divide-y divide-gray-200">
    @forelse ($pengembalian as $item)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 font-bold">{{ $item->user->name ?? 'N/A' }}</td>
        <td class="px-6 py-4">{{ $item->buku->judul ?? 'Buku Dihapus' }}</td>
        <td class="px-6 py-4 text-center">
            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}
        </td>
        <td class="px-6 py-4 text-center">
            {{-- FORM KONFIRMASI --}}
           <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                @csrf 
                {{-- Hapus @method('PATCH') --}}
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded text-xs font-bold">
                    TERIMA BUKU
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center py-10 text-gray-500 italic">Tidak ada data pengajuan kembali.</td>
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