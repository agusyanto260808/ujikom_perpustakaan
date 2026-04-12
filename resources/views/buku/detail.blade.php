<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0">{{ __('Informasi Detail Buku') }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('buku.edit', $buku->idbuku) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm text-white">
                    <i class="bi bi-pencil-square"></i> Edit Buku
                </a>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                        <div class="row g-0">
                            
                            {{-- Sisi Kiri: Gambar Cover --}}
                            <div class="col-md-5 bg-dark d-flex align-items-center justify-content-center p-5 position-relative overflow-hidden" style="min-height: 500px;">
                                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                                     class="img-fluid rounded-3 shadow-lg" 
                                     alt="{{ $buku->judul }}"
                                     style="max-height: 480px; width: auto; object-fit: contain; z-index: 1;">
                                
                                {{-- Efek Dekoratif Background --}}
                                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-50" 
                                     style="background-image: url('{{ $buku->gambar ? asset('storage/'.$buku->gambar) : '' }}'); background-size: cover; background-position: center; filter: blur(30px);">
                                </div>
                            </div>

                            {{-- Sisi Kanan: Informasi Buku --}}
                            <div class="col-md-7">
                                <div class="card-body p-4 p-md-5">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            {{-- Menampilkan Kategori --}}
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                <i class="bi bi-tag-fill me-1"></i> {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                                            </span>
                                            <span class="badge bg-light text-secondary px-3 py-2 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                ID: #{{ $buku->idbuku }}
                                            </span>
                                        </div>
                                        
                                        <h1 class="fw-bold text-dark display-6 mb-2">{{ $buku->judul }}</h1>
                                        <p class="text-muted fs-5">Ditulis oleh <span class="text-dark fw-bold">{{ $buku->penulis }}</span></p>
                                    </div>

                                    <hr class="my-4 opacity-10">

                                    {{-- Detail Spesifikasi --}}
                                    <div class="row g-4 mb-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light p-3 rounded-3 me-3">
                                                    <i class="bi bi-building text-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Penerbit</small>
                                                    <span class="fw-bold">{{ $buku->penerbit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light p-3 rounded-3 me-3">
                                                    <i class="bi bi-calendar-check text-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Tahun Terbit</small>
                                                    <span class="fw-bold">{{ $buku->tahun }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light p-3 rounded-3 me-3">
                                                    <i class="bi bi-stack text-{{ $buku->stok > 0 ? 'success' : 'danger' }} fs-4"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Stok Perpustakaan</small>
                                                    <span class="fw-bold {{ $buku->stok > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $buku->stok }} Buku
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light p-3 rounded-3 me-3">
                                                    <i class="bi bi-clock-history text-info fs-4"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Terakhir Update</small>
                                                    <span class="fw-bold">{{ $buku->updated_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Catatan Tambahan --}}
                                    <div class="bg-light p-4 rounded-4 border-start border-4 border-primary">
                                        <h6 class="fw-bold text-dark mb-2">Catatan Sistem:</h6>
                                        <p class="text-secondary small mb-0 leading-relaxed">
                                            Buku ini merupakan bagian dari koleksi kategori <strong>{{ $buku->kategori->nama_kategori ?? 'Tidak Ada Kategori' }}</strong>. 
                                            Pastikan ketersediaan stok fisik sesuai dengan data digital saat melakukan peminjaman.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">
                            Data dicetak pada: {{ date('d/m/Y H:i') }} &bull; Sistem Informasi Perpustakaan
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-4 { border-radius: 1.25rem !important; }
        .leading-relaxed { line-height: 1.6; }
        .bg-primary-subtle { background-color: #e7f1ff; }
        .bg-dark { background-color: #1a1d20 !important; }
    </style>
</x-app-layout>