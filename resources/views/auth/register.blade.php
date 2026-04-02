<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('Daftar Sebagai')" />
            <select id="role" name="role" onchange="toggleNISN()" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota (Siswa)</option>
                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                <option value="kep_perpus" {{ old('role') == 'kep_perpus' ? 'selected' : '' }}>Kepala Perpustakaan</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4" id="nisn-container">
            <x-input-label for="nisn" :value="__('NISN (Khusus Siswa)')" />
            <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn" :value="old('nisn')" placeholder="Masukkan NISN Anda" />
            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleNISN() {
            const role = document.getElementById('role').value;
            const nisnContainer = document.getElementById('nisn-container');
            const nisnInput = document.getElementById('nisn');

            if (role === 'anggota') {
                nisnContainer.style.display = 'block';
                nisnInput.setAttribute('required', 'required');
            } else {
                nisnContainer.style.display = 'none';
                nisnInput.value = ''; // Kosongkan input jika disembunyikan
                nisnInput.removeAttribute('required');
            }
        }

        // Jalankan saat halaman pertama kali dimuat (untuk menangani 'old value' saat error validasi)
        document.addEventListener('DOMContentLoaded', function() {
            toggleNISN();
        });
    </script>
</x-guest-layout>