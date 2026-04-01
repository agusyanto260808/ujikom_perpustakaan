<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Buku: ') }} {{ $buku->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="judul" :value="__('Judul Buku')" />
                            <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" :value="$buku->judul" required />
                        </div>

                        <div>
                            <x-input-label for="penulis" :value="__('Penulis')" />
                            <x-text-input id="penulis" name="penulis" type="text" class="mt-1 block w-full" :value="$buku->penulis" required />
                        </div>

                        <div>
                            <x-input-label for="penerbit" :value="__('Penerbit')" />
                            <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full" :value="$buku->penerbit" required />
                        </div>

                        <div>
                            <x-input-label for="tahun" :value="__('Tahun Terbit')" />
                            <x-text-input id="tahun" name="tahun" type="number" class="mt-1 block w-full" :value="$buku->tahun" required />
                        </div>

                        <div>
                            <x-input-label for="stok" :value="__('Stok')" />
                            <x-text-input id="stok" name="stok" type="number" class="mt-1 block w-full" :value="$buku->stok" required />
                        </div>

                        <div>
                            <x-input-label for="gambar" :value="__('Ganti Cover (Kosongkan jika tidak diubah)')" />
                            @if($buku->gambar)
                                <img src="{{ asset('storage/'.$buku->gambar) }}" class="w-20 mb-2 rounded">
                            @endif
                            <input id="gambar" name="gambar" type="file" class="mt-1 block w-full text-white" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        <a href="{{ route('buku.index') }}" class="ml-3 text-gray-600 dark:text-gray-400">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>