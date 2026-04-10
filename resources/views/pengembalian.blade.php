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
            <th class="py-3 small text-uppercase text-center">Denda (Est.)</th>
            <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white">
        @forelse ($pengembalian as $item)
        @php
            $tglJatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = now()->startOfDay();
            $isTerlambat = $hariIni->gt($tglJatuhTempo);
            $hariTerlambat = $isTerlambat ? $hariIni->diffInDays($tglJatuhTempo) : 0;
            $nominalDenda = $hariTerlambat * 2000;
        @endphp
        <tr>
            <td class="ps-4 small text-muted">{{ $loop->iteration }}</td>
            <td>
                <div class="fw-bold text-dark">{{ $item->user->name ?? 'N/A' }}</div>
                <small class="text-muted">ID: #{{ $item->idpinjam }}</small>
            </td>
            <td>{{ $item->buku->judul ?? '-' }}</td>
            <td class="text-center">
                <span class="{{ $isTerlambat ? 'text-danger fw-bold' : 'text-dark' }} small">
                    {{ $tglJatuhTempo->format('d/m/Y') }}
                </span>
            </td>
          <td class="text-center">
    @php
        $tglJatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();
        $hariIni = now()->startOfDay();
        $estHariTerlambat = $hariIni->gt($tglJatuhTempo) ? $hariIni->diffInDays($tglJatuhTempo) : 0;
        $estDenda = $estHariTerlambat * 2000;
    @endphp
    
    @if($estDenda > 0)
      <span class="text-muted small">Aman</span>
        
    @else
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
            Rp {{ number_format($estDenda, 0, ',', '.') }}
        </span>
        <div class="small text-muted">{{ $estHariTerlambat }} Hari</div>
    @endif
</td>
            <td class="text-center pe-4">
                @if($item->status == 'proses kembali')
                    <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success px-3 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Terima Buku
                        </button>
                    </form>
                @else
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                        <i class="bi bi-patch-check-fill me-1"></i> Selesai
                    </span>
                @endif
            </td>
        </tr>
        @empty
        {{-- ... row kosong ... --}}
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