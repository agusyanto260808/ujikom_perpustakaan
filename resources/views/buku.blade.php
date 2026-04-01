<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            📚 Daftar Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">

                <!-- Header Action -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Data Buku
                    </h3>

                    <a href="{{ route('buku.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition duration-200">
                        + Tambah Buku
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">

                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Gambar</th>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Penulis</th>
                                <th class="px-6 py-3">Penerbit</th>
                                <th class="px-6 py-3 text-center">Tahun</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($buku as $item)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                                <td class="px-6 py-4 font-medium">
                                    {{ $item->idbuku }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                             class="w-14 h-20 object-cover rounded-lg shadow">
                                    @else
                                        <span class="text-xs italic text-gray-400">No Image</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                                    {{ $item->judul }}
                                </td>

                                <td class="px-6 py-4">{{ $item->penulis }}</td>
                                <td class="px-6 py-4">{{ $item->penerbit }}</td>

                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded text-xs">
                                        {{ $item->tahun }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="bg-green-100 text-green-700 dark:bg-green-700 dark:text-white px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $item->stok }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">

                                        <!-- Edit -->
                                        <a href="{{ route('buku.edit', $item->idbuku) }}"
                                           class="px-3 py-1 text-xs bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg shadow">
                                            Edit
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('buku.destroy', $item->idbuku) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="px-3 py-1 text-xs bg-red-500 hover:bg-red-600 text-white rounded-lg shadow">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-400">
                                    📭 Data buku belum tersedia
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