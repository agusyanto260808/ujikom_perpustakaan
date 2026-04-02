<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Transaksi Peminjaman</h3>
                    </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Peminjam</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Judul Buku</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Tgl Pinjam</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Batas Kembali</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Status</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @forelse ($peminjaman as $pinjam)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
        
        {{-- Kolom Peminjam (Mengambil nama dari relasi User) --}}
        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
            {{ $pinjam->user->name ?? 'User Tidak Ditemukan' }}
            <div class="text-xs text-gray-400 font-normal">{{ $pinjam->user->email ?? '' }}</div>
        </td>

        <td class="px-6 py-4">
            {{ $pinjam->buku->judul }}
        </td>

        <td class="px-6 py-4 text-center">
            {{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d/m/Y') }}
        </td>

        <td class="px-6 py-4 text-center">
            {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d/m/Y') }}
        </td>

        <td class="px-6 py-4 text-center">
            @if($pinjam->status == 'Menunggu')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    Menunggu
                </span>
            @elseif($pinjam->status == 'Dipinjam')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                    Dipinjam
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    Kembali
                </span>
            @endif
        </td>

        <td class="px-6 py-4 text-center">
            <div class="flex justify-center gap-2">
                
                {{-- Aksi Setujui --}}
                @if($pinjam->status == 'Menunggu')
                    <form action="{{ route('peminjaman.update', $pinjam->idpeminjaman) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Dipinjam">
                        <button class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700 transition uppercase">
                            Setujui
                        </button>
                    </form>
                @endif

                {{-- Aksi Kembalikan --}}
                @if($pinjam->status == 'Dipinjam')
                    <form action="{{ route('peminjaman.update', $pinjam->idpeminjaman) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Kembali">
                        <button class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded hover:bg-indigo-700 transition uppercase">
                            Kembalikan
                        </button>
                    </form>
                @endif

                {{-- Aksi Hapus --}}
                <form action="{{ route('peminjaman.destroy', $pinjam->idpeminjaman) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700 transition uppercase" onclick="return confirm('Hapus data?')">
                        Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data peminjaman.</td>
    </tr>
    @endforelse
</tbody>
                            @forelse ($peminjaman as $pinjam)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                           {{-- Jika menggunakan relasi ke tabel User --}}
                                                     {{ $pinjam->user->name ?? 'User Tidak Ditemukan' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $pinjam->nama_peminjam }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $pinjam->buku->judul }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($pinjam->status == 'Dipinjam')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Dipinjam
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Kembali
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                     <div class="flex justify-center gap-2">
        
        {{-- JIKA STATUS MENUNGGU: Muncul tombol Setujui --}}
        @if($pinjam->status == 'Menunggu')
            <form action="{{ route('peminjaman.update', $pinjam->idpeminjaman) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Dipinjam">
                <button class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    Setujui
                </button>
            </form>
        @endif

        {{-- JIKA STATUS DIPINJAM: Muncul tombol Kembalikan --}}
        @if($pinjam->status == 'Dipinjam')
            <form action="{{ route('peminjaman.update', $pinjam->idpeminjaman) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Kembali">
                <button class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Kembalikan
                </button>
            </form>
        @endif

        {{-- Tombol Hapus Tetap Ada --}}
        <form action="{{ route('peminjaman.destroy', $pinjam->idpeminjaman) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition"
                    onclick="return confirm('Hapus data?')">
                Hapus
            </button>
        </form>
    </div>
</td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                    Belum ada data peminjaman yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($peminjaman->hasPages())
                    <div class="mt-6">
                        {{ $peminjaman->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>