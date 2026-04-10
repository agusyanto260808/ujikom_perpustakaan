<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
           <h2 class="h4 fw-bold text-dark mb-0 mt-5">
                <i class="bi bi-arrow-left-right"></i> Kelola Transaksi Peminjaman
            </h2>
            <small class="text-muted mt-5">
                Total Transaksi: <strong>{{ $peminjaman->total() }}</strong>
            </small>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- FILTER KATEGORI BULAN --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="small fw-bold text-secondary mb-1">Cari Berdasarkan Bulan</label>
                            <select name="bulan" class="form-select form-select-sm border-secondary-subtle shadow-none">
                                <option value="">-- Semua Bulan --</option>
                                @for ($m=1; $m<=12; $m++)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary mb-1">Tahun</label>
                            <select name="tahun" class="form-select form-select-sm border-secondary-subtle shadow-none">
                                @php $currentYear = date('Y'); @endphp
                                @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                                    <option value="{{ $y }}" {{ request('tahun', $currentYear) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-5 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold shadow-sm">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @if(request('bulan') || request('tahun'))
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-light border px-3">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD UTAMA --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
    @if(request('bulan'))
        {{-- WAJIB: Tambahkan (int) sebelum request('bulan') --}}
        TRANSAKSI BULAN {{ strtoupper(\Carbon\Carbon::create()->month((int)request('bulan'))->translatedFormat('F')) }}
    @else
        SEMUA TRANSAKSI
    @endif
</h6>
                        <small class="text-secondary">Monitoring arus peminjaman buku</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3 small text-uppercase">No</th>
                                <th class="py-3 small text-uppercase">Peminjam</th>
                                <th class="py-3 small text-uppercase">Buku</th>
                                <th class="py-3 small text-uppercase text-center">Tgl Pinjam</th>
                                <th class="py-3 small text-uppercase text-center">Batas Kembali</th>
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @forelse ($peminjaman as $pinjam)
                            @php $statusLower = strtolower($pinjam->status); @endphp
                            <tr>
                                <td class="ps-4 small text-muted">
                                    {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $pinjam->user->name ?? 'User' }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold text-dark">{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</div>
                                    <span class="badge rounded-pill bg-dark-subtle text-dark small fw-normal">
                                        {{ $pinjam->jumlah }} Buku
                                    </span>
                                </td>

                                <td class="text-center text-secondary small">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @php 
                                        $isOverdue = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->isPast() && $statusLower != 'kembali' && $statusLower != 'selesai';
                                    @endphp
                                    
                                    @if($isOverdue)
                                        <span class="badge bg-danger-subtle text-danger px-3">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-secondary small">
                                            {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center pe-4">
    <div class="d-flex justify-content-center gap-2">
        @if($statusLower == 'menunggu')
            {{-- Tombol Setujui untuk status Menunggu --}}
            <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Dipinjam">
                <button class="btn btn-sm btn-primary fw-bold px-3 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Setujui
                </button>
            </form>

        @elseif($statusLower == 'dipinjam')
            {{-- Tombol Selesaikan untuk status sedang Dipinjam --}}
            <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Kembali">
                <button class="btn btn-sm btn-success fw-bold px-3 shadow-sm">
                    <i class="bi bi-arrow-return-left me-1"></i> Selesaikan
                </button>
            </form>

        @elseif($statusLower == 'kembali' || $statusLower == 'selesai')
            {{-- Tampilan teks jika transaksi sudah selesai (bukan tombol) --}}
            <span class="text-success small fw-bold">
                <i class="bi bi-check-all"></i> Selesai
            </span>
        @endif
    </div>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 opacity-25"></i>
                                    <p class="mt-2">Tidak ada data transaksi ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($peminjaman->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $peminjaman->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>