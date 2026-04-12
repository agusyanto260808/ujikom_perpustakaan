<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.02em; color: #1e293b; }
        .btn-orange { background-color: #ff6b35; color: white; border-radius: 50px; padding: 12px 30px; font-weight: 600; transition: 0.3s; border: none; }
        .btn-orange:hover { background-color: #e85a28; color: white; transform: translateY(-2px); shadow: 0 10px 15px -3px rgba(255, 107, 53, 0.3); }
        .stat-card { border: none; border-radius: 24px; transition: 0.3s; background: #ffffff; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .floating-image { animation: float 6s ease-in-out infinite; width: 100%; max-width: 480px; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .bg-orange-light { background: rgba(255, 107, 53, 0.1); color: #ff6b35; }
    </style>

<div class="py-12 mt-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h1 class="hero-title mb-4">
                        PERPUS <span style="color: #ff6b35;">SMKN 3</span> BANJAR
                    </h1>
                    <p class="text-secondary mb-5 fs-5">
                        Halo <strong>{{ Auth::user()->name }}</strong>, jelajahi ribuan koleksi buku digital dan pantau aktivitas literasimu dalam satu dashboard cerdas.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('katalog_buku.index') }}" class="btn btn-orange shadow-sm">Mulai Membaca</a>
                        <a href="#riwayat" class="btn btn-outline-secondary rounded-pill px-4">Lihat Riwayat</a>
                    </div>
                    
                    <div class="d-flex gap-5 mt-5 pt-4 border-top">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalBuku ?? 0 }}</h3>
                            <p class="small text-muted">Koleksi Buku</p>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalKembali ?? 0 }}</h3>
                            <p class="small text-muted">Buku Kembali</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <img src="{{ asset('storage/logo.png') }}" class="floating-image" alt="Digital Library">
                        <div class="position-absolute top-50 start-50 translate-middle z-n1" style="width: 350px; height: 350px; background: rgba(255, 107, 53, 0.15); filter: blur(80px); border-radius: 50%;"></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="stat-card card p-4 shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Status Pinjaman</p>
                                <h2 class="fw-bold mb-0 text-dark">{{ $totalPinjam ?? 0 }} <span class="fs-6 fw-normal text-muted">Aktif</span></h2>
                            </div>
                            <div class="p-3 rounded-circle bg-orange-light">
                                <i class="bi bi-journal-check fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="stat-card card p-4 shadow-sm">
                        <h6 class="fw-bold mb-3">Tren Literasimu</h6>
                        <canvas id="peminjamanChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <div id="riwayat" class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                        <h5 class="fw-bold mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0 text-muted small">BUKU</th>
                                    <th class="border-0 text-muted small">TGL PINJAM</th>
                                    <th class="border-0 text-muted small">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPinjam ?? [] as $pinjam)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $pinjam->buku->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>
                                        @if($pinjam->status == 'dipinjam')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Berjalan</span>
                                        @else
                                            <span class="badge rounded-pill bg-success-subtle text-success px-3">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Belum ada peminjaman buku.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
               labels: {!! json_encode($labels ?? []) !!}, 
datasets: [{
    label: 'Peminjaman',
    data: {!! json_encode($values ?? []) !!},
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ff6b35'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>