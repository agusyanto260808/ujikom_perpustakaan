<x-app-layout>
    {{-- Slot Header tetap menggunakan struktur Breeze/Laravel agar sinkron dengan layout utama --}}
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark mb-0">
            {{ __('Katalog Perpustakaan') }}
        </h2>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    </x-slot>

    <div class="bg-light min-vh-100 py-5">
        <div class="container">
            
            <div class="row mb-5 justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form method="GET" action="{{ route('katalog_buku.index') }}">
                        <div class="input-group shadow-sm bg-white rounded-pill p-1 border">
                            <span class="input-group-text bg-transparent border-0 ps-4 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 py-2 shadow-none bg-transparent" 
                                   placeholder="Cari judul, penulis, atau genre..." value="{{ request('search') }}">
                            <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">
                                Temukan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                @forelse($buku as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm book-card">
                            <div class="position-relative overflow-hidden" style="aspect-ratio: 2/3;">
                                <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                                     class="card-img-top w-100 h-100 object-fit-cover rounded-top-3" alt="{{ $item->judul }}">
                                
                                <div class="book-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-all">
                                    <a href="{{ route('katalog.show', $item->idbuku) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                        Detail
                                    </a>
                                </div>

                                @if($item->stok > 0)
                                    <span class="badge position-absolute top-0 start-0 m-2 bg-success-subtle text-success border border-success-subtle small">Tersedia</span>
                                @else
                                    <span class="badge position-absolute top-0 start-0 m-2 bg-danger-subtle text-danger border border-danger-subtle small">Habis</span>
                                @endif
                            </div>

                            <div class="card-body p-3">
                                <small class="text-primary fw-bold text-uppercase d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">
                                    {{ Str::limit($item->penulis, 15) }}
                                </small>
                                <h6 class="card-title mb-3 h6 fw-bold">
                                    <a href="{{ route('katalog.show', $item->idbuku) }}" class="text-decoration-none text-dark">
                                        {{ $item->judul }}
                                    </a>
                                </h6>
                                
                                <div class="d-flex justify-content-between align-items-end pt-2 border-top">
                                    <div class="lh-sm">
                                        <small class="text-muted d-block fw-bold" style="font-size: 8px;">STOK</small>
                                        <span class="text-primary fw-bold h6 mb-0">{{ $item->stok ?? 0 }}</span>
                                    </div>
                                    <small class="text-muted small">#{{ $item->idbuku }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-journal-x display-1 text-secondary opacity-25"></i>
                        <p class="mt-3 fs-5 text-muted">Koleksi belum tersedia.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $buku->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Hover Effect */
        .book-card {
            border-radius: 12px;
            transition: transform 0.2s ease-in-out;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-card:hover .book-overlay {
            opacity: 1 !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Badge Customization to match Tailwind look */
        .badge {
            font-size: 10px;
            text-transform: uppercase;
            padding: 4px 8px;
        }

        /* Fix image focus */
        .object-fit-cover {
            object-fit: cover;
        }
    </style>
</x-app-layout>