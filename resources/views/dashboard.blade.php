<x-app-layout>
<x-slot name="header">
    {{-- Tambahkan mt-4 atau mt-5 di sini --}}
    <h2 class="h4 fw-bold text-dark mb-0 mt-5">
        <i class="bi bi-speedometer2 me-2"></i>{{ __('Admin Dashboard') }}
    </h2>
</x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            <div class="row g-4 mb-5">
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">Total Buku</p>
                                    <h2 class="fw-extrabold mb-0">{{ number_format($totalBuku) }}</h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                    <i class="bi bi-book fs-3 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">Anggota Aktif</p>
                                    <h2 class="fw-extrabold mb-0">{{ number_format($totalUser) }}</h2>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                    <i class="bi bi-people fs-3 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">Sedang Dipinjam</p>
                                    <h2 class="fw-extrabold mb-0">{{ number_format($totalPinjam) }}</h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                    <i class="bi bi-arrow-left-right fs-3 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">Selesai/Lunas</p>
                                    <h2 class="fw-extrabold mb-0">{{ number_format($totalKembali) }}</h2>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                    <i class="bi bi-check-circle fs-3 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Akses Cepat Pengelolaan</h5>
                </div>
                <div class="card-body py-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('buku.index') }}" class="btn btn-outline-primary w-100 py-3 shadow-sm transition-all hover-lift">
                                <i class="bi bi-journal-plus d-block fs-2 mb-2"></i>
                                <span class="fw-bold small">Kelola Buku</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-warning w-100 py-3 shadow-sm transition-all hover-lift">
                                <i class="bi bi-cart-check d-block fs-2 mb-2"></i>
                                <span class="fw-bold small">Peminjaman</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-info w-100 py-3 shadow-sm transition-all hover-lift">
                                <i class="bi bi-arrow-counterclockwise d-block fs-2 mb-2"></i>
                                <span class="fw-bold small">Pengembalian</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('kelola_akun.index') }}" class="btn btn-outline-dark w-100 py-3 shadow-sm transition-all hover-lift">
                                <i class="bi bi-person-gear d-block fs-2 mb-2"></i>
                                <span class="fw-bold small">User & Role</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .hover-lift:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .fw-extrabold { font-weight: 800; }
    </style>
</x-app-layout>