<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajukan Pengembalian Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase">Buku yang Sedang Anda Bawa</h3>
                    <p class="text-sm text-gray-500">Klik "Ajukan" jika Anda ingin mengembalikan buku ke perpustakaan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4">Judul Buku</th>
                                <th class="px-6 py-4 text-center">Tgl Pinjam</th>
                                <th class="px-6 py-4 text-center">Batas Kembali</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($peminjaman as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $item->buku->judul }}
                                    <div class="text-[10px] text-blue-500">Jumlah: {{ $item->jumlah }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                    @if(\Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->isPast() && $item->status == 'Dipinjam')
                                        <br><span class="text-[10px] text-red-500 font-black uppercase">Terlambat!</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = strtolower($item->status);
                                        $class = [
                                            'dipinjam' => 'bg-blue-100 text-blue-700',
                                            'proses kembali' => 'bg-amber-100 text-amber-700 animate-pulse',
                                            'kembali' => 'bg-green-100 text-green-700'
                                        ][$status] ?? 'bg-gray-100';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $class }}">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($status == 'dipinjam')
                                        <form action="{{ route('pengembalian.ajukan', $item->idpinjam) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold py-2 px-4 rounded shadow-sm transition">
                                                AJUKAN PENGEMBALIAN
                                            </button>
                                        </form>
                                    @elseif($status == 'proses kembali')
                                        <span class="text-xs text-gray-400 italic">Menunggu Petugas...</span>
                                    @else
                                        <span class="text-xs text-green-600 font-bold">✓ Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada pinjaman aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>