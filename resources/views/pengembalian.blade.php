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

        .form-select-modern {
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
                    <h1 class="fw-bold mb-1 h2">Konfirmasi Pengembalian</h1>
                    <p class="opacity-75 mb-0">Terdapat {{ $pengembalian->total() }} buku dalam antrean konfirmasi</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-white text-success px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-down me-1"></i> Area Petugas
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- FILTER SECTION --}}
        <div class="modern-card p-4 mb-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-secondary mb-2">Bulan Pengajuan</label>
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
                    <button type="submit" class="btn btn-modern flex-grow-1 text-white shadow-sm" style="background: #4158D0; border: none;">
                        <i class="bi bi-search me-1"></i> Cari Pengajuan
                    </button>
                    @if(request('bulan') || request('tahun'))
                        <a href="{{ url()->current() }}" class="btn btn-light btn-modern border">
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
            <div class="px-4 py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    @if(request('bulan'))
                        PENGAJUAN MASUK: {{ strtoupper(\Carbon\Carbon::create()->month((int)request('bulan'))->translatedFormat('F')) }}
                    @else
                        ANTREAN KONFIRMASI BUKU
                    @endif
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Peminjam</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Estimasi Denda</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengembalian as $item)
                        @php
                            $tglJatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();
                            $hariIni = now()->startOfDay();
                            $isTerlambat = $hariIni->gt($tglJatuhTempo);
                            $hariTerlambat = $isTerlambat ? $hariIni->diffInDays($tglJatuhTempo) : 0;
                            $nominalDenda = $hariTerlambat * 2000;
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="status-avatar me-3">
                                        <i class="bi bi-person-check-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->user->name ?? 'N/A' }}</div>
                                        <div class="text-muted small">#ID-{{ $item->idpinjam }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->buku->judul ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="{{ $isTerlambat ? 'badge bg-danger-subtle text-danger' : 'text-secondary' }} px-3 py-2 rounded-pill small fw-medium">
                                    {{ $tglJatuhTempo->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($nominalDenda > 0) 
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-shield-check me-1"></i> Tepat Waktu
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill">
                                        Rp {{ number_format($nominalDenda, 0, ',', '.') }}
                                    </span>
                                    <div class="text-danger small mt-1" style="font-size: 0.7rem;">Terlambat {{ $hariTerlambat }} Hari</div>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($item->status == 'proses kembali')
                                    <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-modern shadow-sm btn-sm px-4">
                                            Konfirmasi Terima
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> Berhasil Kembali
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/success.svg" alt="Empty" style="width: 140px;" class="mb-3 opacity-50">
                                <p class="text-muted">Semua pengajuan sudah diproses!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengembalian->hasPages())
                <div class="px-4 py-4 border-top">
                    {{ $pengembalian->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>