<x-app-layout>
    <x-slot name="header">
        {{-- Load Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h2 class="h4 fw-bold text-dark mb-0">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <form action="{{ route('kelola_akun.store') }}" method="POST">
                                @csrf

                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary rounded-circle me-3" style="width: 10px; height: 30px;"></div>
                                    <h5 class="fw-bold mb-0">Formulir Pendaftaran User</h5>
                                </div>

                                {{-- Grid 2 Kolom --}}
                                <div class="row g-4">
                                    
                                    {{-- Nama Lengkap --}}
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" 
                                            class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                            value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap...">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Alamat Email</label>
                                        <input type="email" id="email" name="email" 
                                            class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                            value="{{ old('email') }}" required placeholder="email@contoh.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Role / Jabatan --}}
                                    <div class="col-md-6">
                                        <label for="role" class="form-label fw-semibold small text-muted text-uppercase">Role / Jabatan</label>
                                        <select name="role" id="role" 
                                            class="form-select form-select-lg @error('role') is-invalid @enderror" 
                                            onchange="toggleNisn(this.value)">
                                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- NISN --}}
                                    <div class="col-md-6" id="nisn_field">
                                        <label for="nisn" class="form-label fw-semibold small text-muted text-uppercase">NISN (Khusus Siswa)</label>
                                        <input type="text" id="nisn" name="nisn" 
                                            class="form-control form-control-lg @error('nisn') is-invalid @enderror" 
                                            value="{{ old('nisn') }}" placeholder="Masukkan nomor induk...">
                                        @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Password --}}
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold small text-muted text-uppercase">Password</label>
                                        <input type="password" id="password" name="password" 
                                            class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                            required placeholder="Minimal 8 karakter">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Konfirmasi Password --}}
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold small text-muted text-uppercase">Konfirmasi Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" 
                                            class="form-control form-control-lg" required placeholder="Ulangi password">
                                    </div>

                                </div>

                                <hr class="my-5 opacity-10">

                                {{-- Tombol --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('kelola_akun.index') }}" class="btn btn-link text-decoration-none text-secondary fw-medium">
                                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm rounded-3 fw-bold">
                                        Daftarkan Pengguna
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleNisn(role) {
            const field = document.getElementById('nisn_field');
            if (role === 'siswa') {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
                document.getElementById('nisn').value = ''; 
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleNisn(document.getElementById('role').value);
        });
    </script>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
        .rounded-4 { border-radius: 1.25rem !important; }
        .form-label { margin-bottom: 0.5rem; }
    </style>
</x-app-layout>