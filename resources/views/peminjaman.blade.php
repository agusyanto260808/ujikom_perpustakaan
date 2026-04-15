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
            overflow: hidden;
            background: #fff;
        }

        .form-select-modern, .search-input {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            transition: 0.3s;
        }

        .form-select-modern:focus {
            box-shadow: 0 0 0 4px rgba(65, 88, 208, 0.1);
            border-color: #4158D0;
        }

        .btn-modern {
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
        }

        .table thead th {
            background-color: #fcfcfd;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody td {
            padding: 18px 20px;
            color: #1e293b;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(65, 88, 208, 0.1);
            color: #4158D0;
        }
    </style>

    {{-- HEADER SECTION --}}
    <div class="header-gradient text-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1 h2">Kelola Transaksi</h1>
                    <p class="opacity-75 mb-0">Total terdapat {{ $peminjaman->total() }} transaksi peminjaman</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-bar-chart-fill me-1"></i> Arus Buku Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- FILTER SECTION --}}
        <div class="modern-card p-4 mb-4">
            <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-secondary mb-2">Bulan</label>
                    <select name="bulan" class="form-select form-select-modern">
                        <option value="">-- Semua Bulan --</option>
                        @for ($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary mb-2">Tahun</label>
                    <select name="tahun" class="form-select form-select-modern">
                        @php $currentYear = date('Y'); @endphp
                        @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun', $currentYear) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern flex-grow-1 shadow-sm" style="background: #4158D0; border: none;">
                        <i class="bi bi-funnel me-1"></i> Filter Data
                    </button>
                    @if(request('bulan') || request('tahun'))
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-light btn-modern border">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 rounded-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TABLE SECTION --}}
        <div class="modern-card">
            <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    @if(request('bulan'))
                        LAPORAN BULAN {{ strtoupper(\Carbon\Carbon::create()->month((int)request('bulan'))->translatedFormat('F')) }}
                    @else
                        SEMUA RIWAYAT PEMINJAMAN
                    @endif
                </h6>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Peminjam</th>
                            <th>Informasi Buku</th>
                            <th class="text-center">Tgl Pinjam</th>
                            <th class="text-center">Batas Tempo</th>
                            <th class="text-center pe-4">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $pinjam)
                        @php $statusLower = strtolower($pinjam->status); @endphp
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $pinjam->user->name ?? 'User' }}</div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">ID: #TRX-{{ $pinjam->idpinjam }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</div>
                                <span class="badge bg-light text-dark border small fw-normal">
                                    {{ $pinjam->jumlah }} Buku
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-secondary small">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}</span>
                            </td>
                            <td class="text-center">
                                @php 
                                    $isOverdue = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->isPast() && !in_array($statusLower, ['kembali', 'selesai']);
                                @endphp
                                
                                @if($isOverdue)
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock-history me-1"></i> {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-dark small fw-medium">{{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
    <div class="d-flex justify-content-center gap-2">
        @if($statusLower == 'menunggu')
            {{-- Tombol Setuju --}}
            <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Dipinjam">
                <button type="submit" class="btn btn-sm btn-primary btn-modern shadow-sm px-3">
                    <i class="bi bi-check-lg"></i> Setuju
                </button>
            </form>

            {{-- Tombol Tolak (Memicu Modal) --}}
            <button type="button" class="btn btn-sm btn-danger btn-modern shadow-sm px-3" 
                    data-bs-toggle="modal" data-bs-target="#modalTolak{{ $pinjam->idpinjam }}">
                <i class="bi bi-x-lg"></i> Tolak
            </button>

            {{-- Modal Alasan Penolakan --}}
            <div class="modal fade" id="modalTolak{{ $pinjam->idpinjam }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 20px;">
                        <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-header border-0 pt-4 px-4">
                                <h5 class="modal-title fw-bold">Alasan Penolakan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-4">
                                <p class="text-muted small">Berikan alasan mengapa permintaan peminjaman buku <strong>{{ $pinjam->buku->judul }}</strong> ini ditolak.</p>
                                <input type="hidden" name="status" value="Ditolak">
                                <textarea name="pesan" class="form-control" rows="3" placeholder="Contoh: Stok buku fisik rusak atau tidak tersedia..." required style="border-radius: 12px;"></textarea>
                            </div>
                            <div class="modal-footer border-0 pb-4 px-4">
                                <button type="button" class="btn btn-light btn-modern shadow-none" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-modern shadow-sm">Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @elseif($statusLower == 'dipinjam')
            {{-- Tombol Kembali --}}
            <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Kembali">
                <button class="btn btn-sm btn-success btn-modern shadow-sm px-4">
                    Selesaikan Pinjaman
                </button>
            </form>
        @elseif($statusLower == 'ditolak')
            <span class="badge bg-danger-subtle text-danger px-4 py-2 rounded-pill fw-bold">
                <i class="bi bi-x-circle"></i> Ditolak
            </span>
        @else
            <span class="badge bg-success-subtle text-success px-4 py-2 rounded-pill fw-bold">
                <i class="bi bi-check-all"></i> Selesai
            </span>
        @endif
    </div>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/searching.svg" alt="Empty" style="width: 140px;" class="mb-3 opacity-50">
                                <p class="text-muted">Tidak ada data transaksi yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($peminjaman->hasPages())
                <div class="px-4 py-4 border-top">
                    {{ $peminjaman->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>