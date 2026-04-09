<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h2 class="h4 fw-bold text-dark mb-0">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <form action="{{ route('kelola_akun.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <h5 class="fw-bold mb-4 text-primary">Informasi Dasar</h5>
                                
                                <div class="row g-4 mb-5">
                                    {{-- Nama --}}
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold text-secondary small text-uppercase">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" 
                                            class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold text-secondary small text-uppercase">Alamat Email</label>
                                        <input type="email" id="email" name="email" 
                                            class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Role --}}
                                    <div class="col-md-6">
                                        <label for="role" class="form-label fw-semibold text-secondary small text-uppercase">Role / Jabatan</label>
                                        <select name="role" id="role" 
                                            class="form-select form-select-lg @error('role') is-invalid @enderror" 
                                            onchange="toggleNisn(this.value)">
                                            <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                            <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- NISN --}}
                                    <div class="col-md-6" id="nisn_field">
                                        <label for="nisn" class="form-label fw-semibold text-secondary small text-uppercase">NISN (Khusus Siswa)</label>
                                        <input type="text" id="nisn" name="nisn" 
                                            class="form-control form-control-lg @error('nisn') is-invalid @enderror" 
                                            value="{{ old('nisn', $user->nisn) }}">
                                        @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password Section --}}
                                <div class="bg-light p-4 rounded-4 border border-dashed mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="fw-bold mb-0 text-dark me-2">Keamanan</h5>
                                        <span class="badge bg-white text-muted border fw-normal italic small">*Kosongkan jika tidak ingin diubah</span>
                                    </div>
                                    
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold text-secondary small text-uppercase">Password Baru</label>
                                            <input type="password" id="password" name="password" class="form-control form-control-lg">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold text-secondary small text-uppercase">Konfirmasi Password Baru</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end mt-5">
                                    <a href="{{ route('kelola_akun.index') }}" class="btn btn-link text-decoration-none text-muted me-3">Batal</a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm rounded-3">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        function toggleNisn(role) {
            const field = document.getElementById('nisn_field');
            if (role === 'siswa') {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentRole = document.getElementById('role').value;
            toggleNisn(currentRole);
        });
    </script>

    <style>
        .border-dashed { border-style: dashed !important; }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
        .rounded-4 { border-radius: 1rem !important; }
    </style>
</x-app-layout>