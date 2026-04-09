<x-app-layout>
    <x-slot name="header">
        {{-- Load Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h2 class="h4 fw-bold text-dark mb-0">
            {{ __('Tambah Buku Baru') }}
        </h2>
    </x-slot>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success rounded-circle me-3" style="width: 10px; height: 30px;"></div>
                                    <h5 class="fw-bold mb-0">Detail Inventaris Buku</h5>
                                </div>

                                {{-- Grid 2 Kolom --}}
                                <div class="row g-4">
                                    
                                    {{-- Judul Buku --}}
                                    <div class="col-md-6">
                                        <label for="judul" class="form-label fw-semibold small text-muted text-uppercase">Judul Buku</label>
                                        <input type="text" id="judul" name="judul" 
                                            class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                            value="{{ old('judul') }}" required placeholder="Contoh: Laskar Pelangi">
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tahun Terbit --}}
                                    <div class="col-md-6">
                                        <label for="tahun" class="form-label fw-semibold small text-muted text-uppercase">Tahun Terbit</label>
                                        <input type="number" id="tahun" name="tahun" 
                                            class="form-control form-control-lg @error('tahun') is-invalid @enderror" 
                                            value="{{ old('tahun') }}" required placeholder="YYYY">
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Penulis --}}
                                    <div class="col-md-6">
                                        <label for="penulis" class="form-label fw-semibold small text-muted text-uppercase">Penulis / Pengarang</label>
                                        <input type="text" id="penulis" name="penulis" 
                                            class="form-control form-control-lg @error('penulis') is-invalid @enderror" 
                                            value="{{ old('penulis') }}" required placeholder="Nama penulis...">
                                        @error('penulis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Stok --}}
                                    <div class="col-md-6">
                                        <label for="stok" class="form-label fw-semibold small text-muted text-uppercase">Stok Awal</label>
                                        <input type="number" id="stok" name="stok" 
                                            class="form-control form-control-lg @error('stok') is-invalid @enderror" 
                                            value="{{ old('stok') }}" required placeholder="0">
                                        @error('stok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Penerbit --}}
                                    <div class="col-md-6">
                                        <label for="penerbit" class="form-label fw-semibold small text-muted text-uppercase">Penerbit</label>
                                        <input type="text" id="penerbit" name="penerbit" 
                                            class="form-control form-control-lg @error('penerbit') is-invalid @enderror" 
                                            value="{{ old('penerbit') }}" required placeholder="Nama perusahaan penerbit...">
                                        @error('penerbit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Cover Buku --}}
                                    <div class="col-md-6">
                                        <label for="gambar" class="form-label fw-semibold small text-muted text-uppercase">Upload Cover Buku</label>
                                        <input type="file" id="gambar" name="gambar" 
                                            class="form-control form-control-lg @error('gambar') is-invalid @enderror">
                                        <div class="form-text mt-2" style="font-size: 0.75rem;">Format: JPG, PNG, atau WebP. Maks 2MB.</div>
                                        @error('gambar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <hr class="my-5 opacity-10">

                                {{-- Tombol Aksi --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('buku.index') }}" class="btn btn-link text-decoration-none text-secondary fw-medium p-0">
                                        Batal & Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm rounded-3 fw-bold">
                                        Simpan Buku Baru
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        }
        .rounded-4 { border-radius: 1.25rem !important; }
        .btn-success { background-color: #198754; border: none; }
        .btn-success:hover { background-color: #157347; }
    </style>
</x-app-layout>