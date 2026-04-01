<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <x-input-label for="judul" :value="__('Judul')" />
                            <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" :value="old('judul', $buku->judul)" required />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun" :value="__('Tahun Terbit')" />
                            <x-text-input id="tahun" name="tahun" type="number" class="mt-1 block w-full" :value="old('tahun', $buku->tahun)" required />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="penulis" :value="__('Penulis')" />
                            <x-text-input id="penulis" name="penulis" type="text" class="mt-1 block w-full" :value="old('penulis', $buku->penulis)" required />
                            <x-input-error :messages="$errors->get('penulis')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stok" :value="__('Stok')" />
                            <x-text-input id="stok" name="stok" type="number" class="mt-1 block w-full" :value="old('stok', $buku->stok)" required />
                            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="penerbit" :value="__('Penerbit')" />
                            <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full" :value="old('penerbit', $buku->penerbit)" required />
                            <x-input-error :messages="$errors->get('penerbit')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="gambar" :value="__('Cover Buku')" />
                            
                            <div class="mt-2 mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Cover saat ini:</p>
                                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                                     class="h-20 w-16 object-cover rounded shadow-sm border border-gray-300 dark:border-gray-700">
                            </div>

                            <input type="file" name="gambar" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm p-1">
                            <p class="mt-1 text-xs text-gray-500 italic">Biarkan kosong jika tidak ingin mengganti cover.</p>
                            <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Batal
                        </a>
                        <x-primary-button class="bg-amber-500 hover:bg-amber-600 active:bg-amber-700">
                            {{ __('Update Buku') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>