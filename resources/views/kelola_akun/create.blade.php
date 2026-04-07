<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                
                <form action="{{ route('kelola_akun.store') }}" method="POST">
                    @csrf

                    {{-- Grid 2 Kolom Agar Sama Dengan Form Buku --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Nama Lengkap --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus placeholder="Nama lengkap user..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Alamat Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required placeholder="email@contoh.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Role / Jabatan --}}
                        <div>
                            <x-input-label for="role" :value="__('Role / Jabatan')" />
                            <select name="role" id="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" onchange="toggleNisn(this.value)">
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- NISN (Otomatis Muncul/Hilang) --}}
                        <div id="nisn_field" class="{{ old('role') == 'siswa' ? '' : 'hidden' }}">
                            <x-input-label for="nisn" :value="__('NISN (Khusus Siswa)')" />
                            <x-text-input id="nisn" name="nisn" type="text" class="mt-1 block w-full" :value="old('nisn')" placeholder="Masukkan NISN..." />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    {{-- Tombol Simpan & Batal --}}
                    <div class="mt-6 flex items-center">
                        <x-primary-button>
                            {{ __('Daftarkan Pengguna') }}
                        </x-primary-button>
                        
                        <a href="{{ route('kelola_akun.index') }}" class="ml-4 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline decoration-indigo-500 underline-offset-4 font-medium">
                            {{ __('Batal') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleNisn(role) {
            const field = document.getElementById('nisn_field');
            if (role === 'siswa') {
                field.classList.remove('hidden');
            } else {
                field.classList.add('hidden');
                document.getElementById('nisn').value = ''; 
            }
        }

        // Jalankan saat load pertama kali jika ada error validation (old value)
        document.addEventListener('DOMContentLoaded', function() {
            toggleNisn(document.getElementById('role').value);
        });
    </script>
</x-app-layout>