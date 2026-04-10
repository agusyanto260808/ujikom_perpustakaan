<x-app-layout>
    <x-slot name="header">
        {{-- BOOTSTRAP + ICON --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0 mt-5">
                <i class="bi bi-box-arrow-in-down"></i> Konfirmasi Pengembalian Buku
            </h2>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill mt-5">
                Antrean: {{ $pengembalian->total() }}
            </span>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- FILTER KATEGORI BULAN & TAHUN --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="small fw-bold text-secondary mb-1">Filter Bulan Pengajuan</label>
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
                                <i class="bi bi-funnel"></i> Tampilkan
                            </button>
                            @if(request('bulan') || request('tahun'))
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light border px-3">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD UTAMA --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">
                        @if(request('bulan'))
                            PENGAJUAN BULAN {{ \Carbon\Carbon::create()->month((int)request('bulan'))->translatedFormat('F') }}
                        @else
                            DAFTAR PENGAJUAN MASUK
                        @endif
                    </h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3 small text-uppercase">No</th>
                                <th class="py-3 small text-uppercase">Peminjam</th>
                                <th class="py-3 small text-uppercase">Buku</th>
                                <th class="py-3 small text-uppercase text-center">Jatuh Tempo</th>
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
    @forelse ($pengembalian as $item)
       {{-- Di dalam loop @forelse ($pengembalian as $item) --}}
@php
    $tglJatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();
    $hariIni = now()->startOfDay();
    
    // Hitung selisih
    $selisihHari = $hariIni->diffInDays($tglJatuhTempo, false); 
    
    $terlambat = $hariIni->gt($tglJatuhTempo) ? $hariIni->diffInDays($tglJatuhTempo) : 0;
    $totalDenda = $terlambat * 2000;

    // TAMBAHKAN BARIS INI:
    $statusSekarang = $item->status; 
@endphp

        <tr>
            <td class="ps-4 small text-muted">
                {{ ($pengembalian->currentPage() - 1) * $pengembalian->perPage() + $loop->iteration }}
            </td>

            <td>
                <div class="fw-bold text-dark">{{ $item->user->name ?? 'N/A' }}</div>
                <small class="text-muted">ID Pinjam: #{{ $item->idpinjam }}</small>
            </td>

            <td>
                <div class="fw-semibold text-dark">{{ $item->buku->judul ?? 'Buku tidak tersedia' }}</div>
                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary small fw-normal">
                    {{ $item->jumlah }} Buku
                </span>
            </td>

            <td class="text-center">
                @if($selisihHari > 0)
                    <span class="text-danger fw-bold small">
                        <i class="bi bi-calendar-x me-1"></i>{{ $tglJatuhTempo->format('d/m/Y') }}
                        <br><small class="fw-normal">(Terlambat {{ $selisihHari }} Hari)</small>
                    </span>
                @else
                    <span class="text-secondary small">
                        {{ $tglJatuhTempo->format('d/m/Y') }}
                    </span>
                @endif
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-2">
                    @if($statusSekarang == 'proses kembali')
                        {{-- Pastikan Form ini yang digunakan di halaman Admin Konfirmasi Pengembalian --}}
<form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-sm btn-success">Terima Buku</button>
</form>
                    @elseif(in_array($statusSekarang, ['kembali', 'selesai']))
                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                            <i class="bi bi-check-all me-1"></i> Selesai
                        </span>
                    @else
                        <span class="badge rounded-pill bg-light text-secondary border px-3 py-2 fw-normal">
                            {{ ucfirst($item->status) }}
                        </span>
                    @endif
                    
                    
                </div>
            </td>   
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-check fs-1 opacity-25"></i>
                <p class="mt-2">Tidak ada pengajuan ditemukan untuk filter ini</p>
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>
                </div>

                @if($pengembalian->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $pengembalian->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>