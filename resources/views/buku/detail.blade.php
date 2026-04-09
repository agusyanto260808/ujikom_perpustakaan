<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0">{{ __('Informasi Detail Buku') }}</h2>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                        <div class="row g-0">
                            
                            {{-- Sisi Kiri: Gambar Cover --}}
                            <div class="col-md-5 bg-dark d-flex align-items-center justify-content-center p-5 position-relative">
                                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                                     class="img-fluid rounded-3 shadow-lg" 
                                     alt="{{ $buku->judul }}"
                                     style="max-height: 450px; min-height: 300px; object-fit: cover; z-index: 1;">
                                
                                {{-- Efek Dekoratif Background --}}
                                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" 
                                     style="background-image: url('{{ $buku->gambar ? asset('storage/'.$buku->gambar) : '' }}'); background-size: cover; background-position: center; filter: blur(20px);">
                                </div>
                            </div>

                            {{-- Sisi Kanan: Informasi Buku --}}
                            <div class="col-md-7">
                                <div class="card-body p-4 p-md-5">
                                    <div class="mb-4">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-2">
                                                <li class="breadcrumb-item small text-uppercase fw-bold text-primary">Koleksi</li>
                                                <li class="breadcrumb-item small text-uppercase fw-bold text-muted active">{{ $buku->penerbit }}</li>
                                            </ol>
                                        </nav>
                                        <h1 class="fw-bold text-dark display-5 mb-2">{{ $buku->judul }}</h1>
                                        <p class="text-muted fs-5">Karya dari <span class="text-primary fw-semibold">{{ $buku->penulis }}</span></p>
                                    </div>

                                    {{-- Info Grid --}}
                                    <div class="row g-3 mb-5">
                                        <div class="col-6">
                                            <div class="p-3 border rounded-3 bg-white shadow-sm">
                                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Tahun Terbit</small>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                                    <span class="fw-bold text-dark">{{ $buku->tahun }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 border rounded-3 bg-white shadow-sm">
                                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Ketersediaan</small>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-box-seam {{ $buku->stok > 0 ? 'text-success' : 'text-danger' }} me-2"></i>
                                                    <span class="fw-bold {{ $buku->stok > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $buku->stok }} <small class="fw-normal">Unit</small>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 border rounded-3 bg-white shadow-sm">
                                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Identitas Koleksi (ISBN / ID)</small>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-qr-code-scan text-info me-2"></i>
                                                    <span class="fw-bold text-dark">#{{ $buku->idbuku }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Deskripsi Ringkas --}}
                                    <div class="border-top pt-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="bi bi-body-text me-2 text-primary"></i>Ringkasan Data
                                        </h6>
                                        <p class="text-secondary leading-relaxed mb-0">
                                            Buku berjudul <strong>{{ $buku->judul }}</strong> ini diterbitkan oleh <strong>{{ $buku->penerbit }}</strong>. 
                                            Saat ini koleksi tersebut berada dalam status pengelolaan perpustakaan dengan riwayat pembaruan data terakhir pada tanggal {{ $buku->updated_at->format('d F Y') }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- Akhir Sisi Kanan --}}
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">
                            Halaman ini bersifat informasi statis &bull; Hubungi bagian Pustakawan untuk bantuan lebih lanjut.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-4 { border-radius: 1.5rem !important; }
        .leading-relaxed { line-height: 1.7; }
        .breadcrumb-item + .breadcrumb-item::before { content: "•"; color: #6c757d; }
    </style>
</x-app-layout>