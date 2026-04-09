<x-app-layout>
    <x-slot name="header">
        {{-- Load Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h2 class="h4 fw-bold text-dark mb-0">
            {{ __('Edit Data Buku') }}
        </h2>
    </x-slot>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-warning rounded-circle me-3" style="width: 10px; height: 30px;"></div>
                                    <h5 class="fw-bold mb-0">Perbarui Informasi Buku</h5>
                                </div>

                                {{-- Grid 2 Kolom --}}
                                <div class="row g-4">
                                    
                                    {{-- Judul Buku --}}
                                    <div class="col-md-6">
                                        <label for="judul" class="form-label fw-semibold small text-muted text-uppercase">Judul Buku</label>
                                        <input type="text" id="judul" name="judul" 
                                            class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                            value="{{ old('judul', $buku->judul) }}" required>
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tahun Terbit --}}
                                    <div class="col-md-6">
                                        <label for="tahun" class="form-label fw-semibold small text-muted text-uppercase">Tahun Terbit</label>
                                        <input type="number" id="tahun" name="tahun" 
                                            class="form-control form-control-lg @error('tahun') is-invalid @enderror" 
                                            value="{{ old('tahun', $buku->tahun) }}" required>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Penulis --}}
                                    <div class="col-md-6">
                                        <label for="penulis" class="form-label fw-semibold small text-muted text-uppercase">Penulis</label>
                                        <input type="text" id="penulis" name="penulis" 
                                            class="form-control form-control-lg @error('penulis') is-invalid @enderror" 
                                            value="{{ old('penulis', $buku->penulis) }}" required>
                                        @error('penulis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Stok --}}
                                    <div class="col-md-6">
                                        <label for="stok" class="form-label fw-semibold small text-muted text-uppercase">Stok Tersedia</label>
                                        <input type="number" id="stok" name="stok" 
                                            class="form-control form-control-lg @error('stok') is-invalid @enderror" 
                                            value="{{ old('stok', $buku->stok) }}" required>
                                        @error('stok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Penerbit --}}
                                    <div class="col-md-6">
                                        <label for="penerbit" class="form-label fw-semibold small text-muted text-uppercase">Penerbit</label>
                                        <input type="text" id="penerbit" name="penerbit" 
                                            class="form-control form-control-lg @error('penerbit') is-invalid @enderror" 
                                            value="{{ old('penerbit', $buku->penerbit) }}" required>
                                        @error('penerbit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Cover Buku --}}
                                    <div class="col-md-6">
                                        <label for="gambar" class="form-label fw-semibold small text-muted text-uppercase">Ganti Cover Buku</label>
                                        
                                        <div class="d-flex align-items-start gap-3 bg-light p-3 rounded-3 border">
                                            <div class="text-center">
                                                <p class="text-xs text-muted mb-1" style="font-size: 0.7rem;">Cover Saat Ini</p>
                                                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                                                     class="rounded shadow-sm border object-cover" 
                                                     style="width: 60px; height: 80px;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" name="gambar" class="form-control form-control-sm @error('gambar') is-invalid @enderror">
                                                <div class="form-text mt-2" style="font-size: 0.75rem;">
                                                    *Biarkan kosong jika tidak ingin mengubah cover.
                                                </div>
                                                @error('gambar')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <hr class="my-5 opacity-10">

                                {{-- Tombol Aksi --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('buku.index') }}" class="btn btn-link text-decoration-none text-secondary fw-medium p-0">
                                        Batal & Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow-sm rounded-3 fw-bold text-white">
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

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
        }
        .rounded-4 { border-radius: 1.25rem !important; }
        .bg-light { background-color: #f8f9fa !important; }
        /* Warna warning untuk tombol update agar berbeda dengan tombol daftar */
        .btn-warning { background-color: #f59e0b; border-color: #f59e0b; }
        .btn-warning:hover { background-color: #d97706; border-color: #d97706; color: white; }
    </style>
</x-app-layout>