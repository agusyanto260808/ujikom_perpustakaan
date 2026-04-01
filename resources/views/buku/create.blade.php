<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Buku Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="judul" :value="__('Judul')" />
                            <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" :value="old('judul')" required />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun" :value="__('Tahun Terbit')" />
                            <x-text-input id="tahun" name="tahun" type="number" class="mt-1 block w-full" :value="old('tahun')" required />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="penulis" :value="__('Penulis')" />
                            <x-text-input id="penulis" name="penulis" type="text" class="mt-1 block w-full" :value="old('penulis')" required />
                            <x-input-error :messages="$errors->get('penulis')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stok" :value="__('Stok')" />
                            <x-text-input id="stok" name="stok" type="number" class="mt-1 block w-full" :value="old('stok')" required />
                            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="penerbit" :value="__('Penerbit')" />
                            <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full" :value="old('penerbit')" required />
                            <x-input-error :messages="$errors->get('penerbit')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="gambar" :value="__('Cover Buku')" />
                            <input type="file" name="gambar" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Buku') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>