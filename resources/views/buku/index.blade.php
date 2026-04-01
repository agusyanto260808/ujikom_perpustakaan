<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Koleksi Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Data Master Buku</h3>
                    <a href="{{ route('buku.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Buku
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">No</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Gambar</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Judul</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600">Penulis</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Stok</th>
                                <th class="px-6 py-3 border-b dark:border-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($buku as $i => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex-shrink-0 h- w-7 overflow-hidden rounded shadow-sm border dark:border-gray-600">
                                        <img class="h-full w-full object-cover" 
                                             src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                                             alt="{{ $item->judul }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->penulis }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->stok <= 0)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 uppercase">
                                            Habis
                                        </span>
                                    @elseif($item->stok <= 3)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            {{ $item->stok }} (Limit)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ $item->stok }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('buku.edit', $item->idbuku) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 active:bg-amber-700 transition ease-in-out duration-150">
                                        Edit
                                    </a>

                                    <form action="{{ route('buku.destroy', $item->idbuku) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Yakin hapus data?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                    Belum ada data buku yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>