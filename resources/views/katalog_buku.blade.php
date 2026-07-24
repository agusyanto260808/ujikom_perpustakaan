<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </x-slot>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Jarak aman dari navbar */
        .main-wrapper { padding-top: 80px; min-height: 100vh; }

        /* Header Section */
        .page-header {
            padding: 2.5rem 0;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 3rem;
        }

        /* Search Bar Modern */
        .search-container {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.3s ease;
        }
        .search-container:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* Book Card Style */
        .book-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Overlay Detail */
        .book-overlay {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .book-card:hover .book-overlay { opacity: 1; }

        /* Badge Custom */
        .badge-status {
            font-size: 0.65rem;
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        /* Skeleton Pulse */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton-pulse 1.5s infinite;
        }
        @keyframes skeleton-pulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .btn-indigo {
            background: #4f46e5;
            color: white;
            border: none;
            transition: all 0.2s;
        }
        .btn-indigo:hover {
            background: #4338ca;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }
    </style>

    <div class="main-wrapper">
        <div class="page-header">
            <div class="container text-center">
                <h2 class="fw-bold text-dark mb-2">Katalog Koleksi Buku</h2>
                <p class="text-muted mb-4">Temukan ribuan referensi literatur untuk mendukung studimu</p>
                
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <form method="GET" action="{{ route('katalog_buku.index') }}">
                            <div class="input-group search-container rounded-pill p-1">
                                <span class="input-group-text bg-transparent border-0 ps-4 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0 py-2 shadow-none bg-transparent" 
                                       placeholder="Cari judul, penulis, atau genre..." value="{{ request('search') }}">
                               <button class="btn btn-indigo rounded-pill px-4 fw-bold" 
        type="submit" 
        style="position: relative; z-index: 0;">
    Cari Buku
</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pb-5">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4" id="book-container">
                @forelse($buku as $index => $item)
                    <div class="col book-item {{ $index >= 5 ? 'd-none' : '' }}">
                        <div class="card h-100 book-card">
                            <div class="position-relative overflow-hidden skeleton" style="aspect-ratio: 2/3;">
                                <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                                    class="w-100 h-100 object-fit-cover lazy-img" 
                                    style="opacity: 0; transition: opacity 0.5s;"
                                    alt="{{ $item->judul }}">
                                
                                <div class="book-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                    <a href="{{ route('katalog.show', $item->idbuku) }}" class="btn btn-white btn-sm rounded-pill px-3 fw-bold bg-white text-dark shadow">
                                        Lihat Detail
                                    </a>
                                </div>

                                @if($item->stok_tersedia > 0)
                                    <span class="badge badge-status position-absolute top-0 start-0 m-3 bg-success text-white shadow-sm">Tersedia</span>
                                @else
                                    <span class="badge badge-status position-absolute top-0 start-0 m-3 bg-danger text-white shadow-sm">Habis</span>
                                @endif
                            </div>

                            <div class="card-body p-3">
                                <small class="text-indigo fw-bold text-uppercase d-block mb-1" style="font-size: 10px; color: #4f46e5;">
                                    {{ Str::limit($item->penulis, 20) }}
                                </small>
                                <h6 class="card-title mb-3 fw-bold" style="font-size: 0.95rem; line-height: 1.4;">
                                    <a href="{{ route('katalog.show', $item->idbuku) }}" class="text-decoration-none text-dark hover-indigo">
                                        {{ $item->judul }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 9px; font-weight: 700;">STOK</small>
                                        <span class="fw-bold text-dark">{{ $item->stok_tersedia ?? 0 }}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block" style="font-size: 9px; font-weight: 700;">KODE</small>
                                        <span class="text-secondary small">#{{ $item->idbuku }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                        <p class="mt-3 fs-5 text-muted">Maaf, buku yang Anda cari tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            @if($buku->count() > 5)
                <div class="text-center mt-5">
                    <button id="btn-toggle-books" class="btn btn-indigo rounded-pill px-5 py-3 fw-bold shadow">
                        <span id="btn-content">
                            <i class="bi bi-grid-3x3-gap me-2"></i> Jelajahi Semua Buku
                        </span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lazy Load Handler
            const images = document.querySelectorAll('.lazy-img');
            images.forEach(img => {
                const handleLoad = () => {
                    img.style.opacity = '1';
                    img.parentElement.classList.remove('skeleton');
                };
                if (img.complete) handleLoad();
                else img.addEventListener('load', handleLoad);
            });

            // Toggle Books Handler
            const btnToggle = document.getElementById('btn-toggle-books');
            const btnContent = document.getElementById('btn-content');
            const allItems = document.querySelectorAll('.book-item');
            let isExpanded = false;

            if (btnToggle) {
                btnToggle.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    allItems.forEach((item, index) => {
                        if (index >= 5) item.classList.toggle('d-none');
                    });

                    btnContent.innerHTML = isExpanded 
                        ? '<i class="bi bi-chevron-up me-2"></i> Sembunyikan' 
                        : '<i class="bi bi-grid-3x3-gap me-2"></i> Jelajahi Semua Buku';
                    
                    if (!isExpanded) {
                        document.getElementById('book-container').scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
</x-app-layout>