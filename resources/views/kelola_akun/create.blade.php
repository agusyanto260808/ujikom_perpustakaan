<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Tambah') }} <span class="text-indigo-600">Pengguna</span>
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Daftarkan akun petugas atau siswa baru ke sistem.</p>
            </div>
            <a href="{{ route('kelola_akun.index') }}" class="flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 font-bold text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-10">
                    <form action="{{ route('kelola_akun.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700 ml-1" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" :value="old('name')" required autofocus placeholder="Masukkan nama lengkap..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Email Address')" class="font-bold text-gray-700 ml-1" />
                            <x-text-input id="email" name="email" type="email" class="block mt-1 w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" :value="old('email')" required placeholder="contoh@email.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="role" :value="__('Role / Jabatan')" class="font-bold text-gray-700 ml-1" />
                            <select name="role" id="role" class="block mt-1 w-full border-gray-200 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm font-medium" onchange="toggleNisn(this.value)">
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div id="nisn_field" class="mb-6 hidden">
                            <x-input-label for="nisn" :value="__('NISN Siswa')" class="font-bold text-gray-700 ml-1" />
                            <x-text-input id="nisn" name="nisn" type="text" class="block mt-1 w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" :value="old('nisn')" placeholder="Masukkan nomor induk siswa..." />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="font-bold text-gray-700 ml-1" />
                                <x-text-input id="password" name="password" type="password" class="block mt-1 w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-bold text-gray-700 ml-1" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-lg shadow-indigo-100 transition-all duration-300">
                                Daftarkan Akun Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleNisn(role) {
            const field = document.getElementById('nisn_field');
            const input = document.getElementById('nisn');
            
            if (role === 'siswa') {
                field.classList.remove('hidden');
            } else {
                field.classList.add('hidden');
                input.value = ''; // Reset input jika bukan siswa
            }
        }

        // Inisialisasi awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            toggleNisn(document.getElementById('role').value);
        });
    </script>
</x-app-layout>