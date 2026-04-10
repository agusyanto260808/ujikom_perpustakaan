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

            {{-- Bagian Grid Buku --}}
{{-- Grid Buku --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4" id="book-container">
    @forelse($buku as $index => $item)
        {{-- Index dimulai dari 0, jadi $index >= 5 artinya dari buku ke-6 dst akan disembunyikan --}}
        <div class="col book-item {{ $index >= 5 ? 'd-none' : '' }}">
            <div class="card h-100 border-0 shadow-sm book-card">
                {{-- ... isi card Anda ... --}}
                <div class="position-relative overflow-hidden" style="aspect-ratio: 2/3;">
                    <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                         class="card-img-top w-100 h-100 object-fit-cover rounded-top-3 lazy-img" 
                         alt="{{ $item->judul }}">
                    
                    <div class="book-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-all">
                        <a href="{{ route('katalog.show', $item->idbuku) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm">
                            Detail
                        </a>
                    </div>
                    {{-- Badge Status --}}
                    @if($item->stok_tersedia > 0)
                        <span class="badge position-absolute top-0 start-0 m-2 bg-success-subtle text-success border border-success-subtle small">Tersedia</span>
                    @else
                        <span class="badge position-absolute top-0 start-0 m-2 bg-danger-subtle text-danger border border-danger-subtle small">Habis</span>
                    @endif
                </div>

                <div class="card-body p-3">
                    <small class="text-primary fw-bold text-uppercase d-block mb-1" style="font-size: 10px;">
                        {{ Str::limit($item->penulis, 15) }}
                    </small>
                    <h6 class="card-title mb-3 h6 fw-bold">
                         <a href="{{ route('katalog.show', $item->idbuku) }}" class="text-decoration-none text-dark">
                            {{ $item->judul }}
                        </a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-end pt-2 border-top">
                        <div class="lh-sm">
                            <small class="text-muted d-block fw-bold" style="font-size: 8px;">TERSEDIA</small>
                            <span class="text-primary fw-bold h6 mb-0">{{ $item->stok_tersedia ?? 0 }}</span>
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

{{-- Tombol Lihat Semua --}}
@if($buku->count() > 5)
    <div class="text-center mt-5" id="load-more-container">
        <button id="btn-toggle-books" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
            <span id="btn-content">
                <i class="bi bi-grid-3x3-gap me-2"></i> Lihat Semua Buku
            </span>
        </button>
    </div>
@endif



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
        /* Efek Lazy Load & Skeleton */
.lazy-img {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    background-color: #f0f0f0; /* Warna placeholder */
}

/* Skeleton Pulse Animation */
.position-relative.overflow-hidden {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-pulse 1.5s infinite ease-in-out;
}

@keyframes skeleton-pulse {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Hapus animasi skeleton setelah gambar dimuat (opsional via JS) */
.loaded-card {
    animation: none !important;
    background: none !important;
}
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const images = document.querySelectorAll('.lazy-img');
        images.forEach(img => {
            if (img.complete) {
                img.style.opacity = '1';
                img.parentElement.classList.add('loaded-card');
            }
            img.addEventListener('load', function() {
                this.style.opacity = '1';
                this.parentElement.classList.add('loaded-card');
            });
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnToggle = document.getElementById('btn-toggle-books');
    const btnContent = document.getElementById('btn-content');
    const allBookItems = document.querySelectorAll('.book-item');
    const bookContainer = document.getElementById('book-container');

    // Fungsi untuk mematikan efek skeleton dan menampilkan gambar
    const revealImage = (item) => {
        const img = item.querySelector('.lazy-img');
        if (img) {
            img.style.opacity = '1';
            item.querySelector('.position-relative').classList.add('loaded-card');
        }
    };

    if (btnToggle) {
        let isExpanded = false; // Status: apakah semua buku sedang tampil?

        btnToggle.addEventListener('click', function() {
            if (!isExpanded) {
                // --- MODE: TAMPILKAN SEMUA ---
                allBookItems.forEach(item => {
                    item.classList.remove('d-none');
                    revealImage(item);
                });

                // Ubah teks tombol
                btnContent.innerHTML = '<i class="bi bi-chevron-up me-2"></i> Lihat Lebih Sedikit';
                isExpanded = true;
            } else {
                // --- MODE: SEMBUNYIKAN KEMBALI (PERDIKIT) ---
                allBookItems.forEach((item, index) => {
                    if (index >= 5) {
                        item.classList.add('d-none');
                    }
                });

                // Scroll halus ke atas container buku agar posisi user tidak hilang
                bookContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Kembalikan teks tombol
                btnContent.innerHTML = '<i class="bi bi-grid-3x3-gap me-2"></i> Lihat Semua Buku';
                isExpanded = false;
            }
        });
    }

    // Jalankan reveal untuk 5 buku pertama yang sudah muncul
    allBookItems.forEach((item, index) => {
        if (index < 5) revealImage(item);
    });
});
</script>
</x-app-layout>