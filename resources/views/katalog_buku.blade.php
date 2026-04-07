<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h2 class="h4 font-weight-bold text-white mb-0">
            📚 {{ __('Katalog Buku') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        
        <div class="row mb-5 justify-content-center">
            <div class="col-md-6">
                <form method="GET" action="{{ route('katalog_buku.index') }}" class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control border-0 py-3 ps-4 bg-secondary text-white placeholder-light" 
                           placeholder="Cari judul buku atau penulis..." value="{{ request('search') }}"
                           style="border-radius: 50px 0 0 50px;">
                    <button class="btn btn-primary px-4" type="submit" style="border-radius: 0 50px 50px 0;">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </form>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-6 g-4">
            @forelse($buku as $item)
                <div class="col">
                    <div class="card h-100 border-secondary bg-dark shadow-sm hover-card transition" style="border-radius: 15px; overflow: hidden;">
                        
                        <a href="{{ route('katalog.show', $item->idbuku) }}" class="position-relative d-block overflow-hidden" style="aspect-ratio: 3/4;">
                            <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                                 class="card-img-top w-100 h-100 object-fit-cover transition-transform" 
                                 alt="{{ $item->judul }}">
                            
                            <div class="position-absolute inset-0 bg-black bg-opacity-10 transition-opacity"></div>

                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary shadow-sm small">
                                <small>ID</small>
                            </span>

                            <button class="position-absolute top-0 end-0 m-2 btn btn-link text-danger p-0 shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                </svg>
                            </button>
                        </a>

                        <div class="card-body p-3">
                            <p class="text-secondary small mb-1 text-uppercase text-truncate" style="font-size: 0.7rem;">
                                {{ $item->penulis }}
                            </p>
                            <h6 class="card-title fw-bold text-white mb-2 text-limit-2" style="font-size: 0.9rem; line-height: 1.2; height: 2.2rem;">
                                <a href="{{ route('katalog.show', $item->idbuku) }}" class="text-decoration-none text-white hover-blue">
                                    {{ $item->judul }}
                                </a>
                            </h6>
                            
                            <div class="mt-auto">
                                <p class="fw-bold text-info mb-1" style="font-size: 0.9rem;">
                                    Stok: {{ $item->stok ?? 0 }}
                                </p>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success rounded-pill px-2" style="font-size: 0.7rem;">
                                    Tersedia
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-secondary">Buku tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $buku->links() }}
        </div>
    </div>

    <style>
        /* Agar menyatu dengan background gelap aplikasi Anda */
        body { background-color: #0f172a; } /* Sesuaikan dengan warna background gelap Anda */

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4) !important;
            border-color: #3b82f6 !important; /* Warna biru saat di-hover */
        }
        .hover-card img:hover {
            transform: scale(1.1);
        }
        .transition {
            transition: all 0.3s ease;
        }
        .transition-transform {
            transition: transform 0.5s ease;
        }
        .text-limit-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hover-blue:hover {
            color: #3b82f6 !important;
        }
        .object-fit-cover {
            object-fit: cover;
        }
        .placeholder-light::placeholder {
            color: #ccc;
        }
    </style>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</x-app-layout>