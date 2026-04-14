<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
        }

        .header-gradient {
            background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
            border-radius: 0 0 30px 30px;
            padding: 80px 0 100px 0;
            margin-bottom: -60px;
        }

        .modern-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: #fff;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .icon-shape {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
        }

        .btn-modern {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: white;
            color: #1e293b;
        }

        .btn-modern:hover {
            border-color: #4158D0;
            color: #4158D0;
            background: rgba(65, 88, 208, 0.02);
        }

        .fw-extrabold { font-weight: 800; }
    </style>

    {{-- HEADER SECTION --}}
    <div class="header-gradient text-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1 h2">
                        <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                    </h1>
                    <p class="opacity-75 mb-0">Statistik aktivitas perpustakaan dalam 7 hari terakhir.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- STATS SECTION --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="modern-card hover-lift h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-secondary small fw-bold text-uppercase mb-1">Total Buku</p>
                            <h2 class="fw-extrabold mb-0 text-dark">{{ number_format($totalBuku) }}</h2>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-book fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="modern-card hover-lift h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-secondary small fw-bold text-uppercase mb-1">Anggota</p>
                            <h2 class="fw-extrabold mb-0 text-dark">{{ number_format($totalUser) }}</h2>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="modern-card hover-lift h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-secondary small fw-bold text-uppercase mb-1">Dipinjam</p>
                            <h2 class="fw-extrabold mb-0 text-dark">{{ number_format($totalPinjam) }}</h2>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-arrow-left-right fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="modern-card hover-lift h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-secondary small fw-bold text-uppercase mb-1">Lunas</p>
                            <h2 class="fw-extrabold mb-0 text-dark">{{ number_format($totalKembali) }}</h2>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info">
                            <i class="bi bi-check-circle fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DIAGRAM SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="modern-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Tren Peminjaman Buku</h5>
                            <p class="text-muted small mb-0">Jumlah buku yang dipinjam per hari dalam seminggu terakhir</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-pill px-3 border" type="button">
                                <i class="bi bi-calendar3 me-2"></i>7 Hari Terakhir
                            </button>
                        </div>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="borrowingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="modern-card p-4">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold mb-0 text-dark">Akses Cepat Pengelolaan</h5>
            </div>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <a href="{{ route('buku.index') }}" class="btn-modern h-100 py-4 hover-lift shadow-sm">
                        <i class="bi bi-journal-plus fs-1 mb-2 text-primary"></i>
                        <span class="small fw-bold">Kelola Buku</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('peminjaman.index') }}" class="btn-modern h-100 py-4 hover-lift shadow-sm">
                        <i class="bi bi-cart-check fs-1 mb-2 text-warning"></i>
                        <span class="small fw-bold">Peminjaman</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('pengembalian.index') }}" class="btn-modern h-100 py-4 hover-lift shadow-sm">
                        <i class="bi bi-arrow-counterclockwise fs-1 mb-2 text-info"></i>
                        <span class="small fw-bold">Pengembalian</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('kelola_akun.index') }}" class="btn-modern h-100 py-4 hover-lift shadow-sm">
                        <i class="bi bi-person-gear fs-1 mb-2 text-dark"></i>
                        <span class="small fw-bold">User & Role</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
    const ctx = document.getElementById('borrowingChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(65, 88, 208, 0.2)');
    gradient.addColorStop(1, 'rgba(200, 80, 192, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            // Mengambil array labels dari controller
            labels: {!! json_encode($labels) !!}, 
            datasets: [{
                label: 'Jumlah Pinjaman',
                // Mengambil array values dari controller
                data: {!! json_encode($values) !!}, 
                borderColor: '#4158D0',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4158D0',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Buku dipinjam';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 } // Biar angkanya bulat (tidak 1.5, 2.5)
                }
            }
        }
    });
</script>
</x-app-layout>