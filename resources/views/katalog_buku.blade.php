<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Katalog Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <form method="GET" action="{{ route('katalog_buku.index') }}" class="flex gap-2">
                        <input type="text" name="search" placeholder="Cari judul atau penulis..." 
                               class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm w-full"
                               value="{{ request('search') }}">
                        <x-primary-button>Cari</x-primary-button>
                    </form>
                </div>

              <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($buku as $item)
        <div class="bg-white dark:bg-gray-700 p-4 rounded-xl shadow-md border dark:border-gray-600">
            <div class="w-full h-56 mb-4 overflow-hidden rounded-lg bg-gray-200">
                <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                     class="w-full h-full object-cover" alt="{{ $item->judul }}">
            </div>

            <h3 class="font-bold text-gray-900 dark:text-white">{{ $item->judul }}</h3>
            <p class="text-sm text-gray-500">{{ $item->penulis }}</p>

            <div class="mt-4 border-t pt-4">
                <a href="{{ route('katalog.show', $item->idbuku) }}">
                    <x-primary-button class="w-full justify-center bg-indigo-600">
                        Detail & Pinjam
                    </x-primary-button>
                </a>
            </div>
        </div>
    @empty
        <p class="col-span-full text-center">Buku tidak ditemukan.</p>
    @endforelse
</div>

                <div class="mt-6">
                    {{-- Ini sekarang akan berfungsi karena variabel $buku tidak tertimpa --}}
                    {{ $buku->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>