<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0 mt-5">
                <i class="bi bi-database-fill-gear"></i> Pusat Laporan Perpustakaan Terpadu
            </h2>
            <small class="text-muted mt-5">
                Periode: <strong>{{ $nama_bulan }} {{ $tahun }}</strong>
            </small>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">
<div class="print-only">
    <div class="d-flex align-items-center pb-3 mb-4" style="border-bottom: 3px solid #000; padding-bottom: 15px;">
        
        {{-- Logo: Pastikan max-height tidak terlalu besar agar tidak menekan garis --}}
        <div class="me-4">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" style="max-height: 80px; width: auto; display: block;">
        </div>

        {{-- Teks Header --}}
        <div class="text-start flex-grow-1">
            <h4 class="mb-1 fw-bold text-uppercase" style="letter-spacing: 1px; line-height: 1.2;">
                Perpustakaan Terpadu Digital
            </h4>
            <p class="mb-0 small" style="font-size: 14px;">
Jl. Julaeni, RT/RW 5/2, Dsn. Langensari, Kel. Langensari, </p>
<P>Kec. Langensari, Kota Banjar, Jawa Barat 46341</p>
            <p class="mb-0 small" style="font-size: 14px;">
smkn3banjar@ymail.com | Telp: (0265)2734141</p>
        </div>
    </div>
</div>
            {{-- TAB NAVIGASI (no-print) --}}
            <div class="no-print mb-4">
                <ul class="nav nav-pills bg-white p-2 rounded-3 shadow-sm d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold small" data-bs-toggle="pill" data-bs-target="#pills-pinjam">
                            <i class="bi bi-box-arrow-right me-1"></i> Peminjaman
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small" data-bs-toggle="pill" data-bs-target="#pills-kembali">
                            <i class="bi bi-box-arrow-in-left me-1"></i> Pengembalian
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small" data-bs-toggle="pill" data-bs-target="#pills-denda">
                            <i class="bi bi-cash-stack me-1"></i> Denda
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small" data-bs-toggle="pill" data-bs-target="#pills-buku">
                            <i class="bi bi-journals me-1"></i> Inventaris Buku
                        </button>
                    </li>
                </ul>
            </div>

            {{-- FILTER SECTION --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4 no-print">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('laporan.index') }}" class="row g-2 align-items-end">
                        <input type="hidden" name="active_tab" id="active_tab_input" value="{{ request('active_tab', 'pills-pinjam') }}">
                        <div class="col-md-3">
    <label class="small fw-bold text-secondary mb-1">Bulan</label>
    <select name="bulan" class="form-select form-select-sm shadow-none">
        @foreach(range(1, 12) as $m)
            {{-- Menggunakan $bulan --}}
            <option value="{{ $m }}" {{ (int)$bulan == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
            </option>
        @endforeach
    </select>
</div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary mb-1">Tahun</label>
                            <select name="tahun" class="form-select form-select-sm shadow-none">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ (int)$tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-dark px-4 fw-bold shadow-sm">
                                <i class="bi bi-filter-left"></i> Terapkan Filter
                            </button>
                            <button type="button" onclick="window.print()" class="btn btn-sm btn-primary px-4 fw-bold shadow-sm">
                                <i class="bi bi-printer"></i> Cetak Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                
                {{-- 1. TAB PEMINJAMAN --}}
                <div class="tab-pane fade show active" id="pills-pinjam">
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <h3 class="fw-bold text-uppercase mb-1">Laporan Peminjaman</h3>
                                <p class="text-secondary small">Ringkasan transaksi keluar buku periode {{ $nama_bulan }} {{ $tahun }}</p>
                                <hr class="mx-auto border-2 border-primary" style="width: 50px;">
                            </div>
                            
                            {{-- Pastikan file ini ada atau ganti dengan tabel manual --}}
                            @if(View::exists('laporan.partials.table-peminjaman'))
                                @include('laporan.partials.table-peminjaman')
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="bg-light">
                                            <tr class="text-center small fw-bold">
                                                <th>No</th>
                                                <th class="text-start">Nama Peminjam</th>
                                                <th class="text-start">Judul Buku</th>
                                                <th>Tgl Pinjam</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Di Tab Peminjaman, pastikan isinya seperti ini --}}
@foreach($laporan as $item)
<tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td>{{ $item->user->name }}</td>
    <td>{{ $item->buku->judul }}</td>
    <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
    <td class="text-center">
        <span class="badge {{ $item->status == 'Kembali' ? 'bg-success' : 'bg-warning' }}">
            {{ $item->status }}
        </span>
    </td>
</tr>
@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @include('partials.laporan-footer')
                            <div class="print-only mt-5"> 
</div>
                        </div>
                    </div>
                </div>

                {{-- 2. TAB PENGEMBALIAN --}}
                <div class="tab-pane fade" id="pills-kembali">
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <h3 class="fw-bold text-uppercase mb-1 text-success">Laporan Pengembalian</h3>
                                <p class="text-secondary small">Catatan buku yang telah diterima kembali</p>
                                <hr class="mx-auto border-2 border-success" style="width: 50px;">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border">
                                    <thead class="bg-success bg-opacity-10 text-success">
                                        <tr class="text-center small fw-bold">
                                            <th>No</th>
                                            <th class="text-start">Nama Siswa</th>
                                            <th class="text-start">Buku</th>
                                            <th>Tgl Pinjam</th>
                                            <th>Tgl Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
{{-- 2. TAB PENGEMBALIAN --}}
@forelse($laporan->filter(function($item) use ($bulan, $tahun) {
    // Memastikan data pengembalian ada dan kolom 'tanggalkembali' sesuai
    return $item->pengembalian && 
           date('m', strtotime($item->pengembalian->tanggalkembali)) == $bulan &&
           date('Y', strtotime($item->pengembalian->tanggalkembali)) == $tahun;
}) as $item)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $item->user->name }}</td>
        <td>{{ $item->buku->judul }}</td>
        <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
        <td class="text-success text-center">
            {{-- Menggunakan Carbon untuk memformat tanggalkembali --}}
            {{ \Carbon\Carbon::parse($item->pengembalian->tanggalkembali)->format('d/m/Y') }}
        </td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada buku kembali periode ini.</td></tr>
@endforelse
                                    </tbody>
                                </table>
                            </div>
                            @include('partials.laporan-footer')
                        </div>
                    </div>
                </div>

                {{-- 3. TAB DENDA --}}
                {{-- 3. TAB DENDA (Bagian yang Diperbaiki) --}}
<div class="tab-pane fade" id="pills-denda">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-5">
            <div class="text-center mb-5">
                <h3 class="fw-bold text-uppercase mb-1 text-danger">Laporan Pendapatan Denda</h3>
                <p class="text-secondary small">Total keterlambatan siswa bulan ini</p>
                <hr class="mx-auto border-2 border-danger" style="width: 50px;">
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="bg-danger bg-opacity-10 text-danger text-uppercase small fw-bold">
                        <tr class="text-center">
                            <th>No</th>
                            <th class="text-start">Siswa</th>
                            <th>Keterlambatan</th>
                            <th class="text-end">Jumlah Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalDenda = 0; $noDenda = 1; @endphp
                        @foreach($laporan as $item)
                            @if($item->pengembalian && $item->pengembalian->denda)
                                @php $totalDenda += $item->pengembalian->denda->jumlah; @endphp
                                <tr>
                                    <td class="text-center">{{ $noDenda++ }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td class="text-center">
                                        {{ $item->pengembalian->denda->hari_terlambat }} Hari
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item->pengembalian->denda->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        
                        @if($noDenda == 1)
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data denda pada periode ini.</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">TOTAL SELURUH DENDA:</td>
                            <td class="text-end text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @include('partials.laporan-footer')
        </div>
    </div>
</div>

                {{-- 4. TAB BUKU (INVENTARIS) --}}
                <div class="tab-pane fade" id="pills-buku">
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <h3 class="fw-bold text-uppercase mb-1 text-dark">Laporan Inventaris Buku</h3>
                                <p class="text-secondary small">Kondisi Stok & Koleksi Perpustakaan</p>
                                <hr class="mx-auto border-2 border-dark" style="width: 50px;">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-dark text-white text-uppercase small">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th class="text-start">Judul Buku</th>
                                            <th>Stok Total</th>
                                            <th>Tersedia</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($buku_all as $buku)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-bold">{{ $buku->judul }}</td>
                                            <td class="text-center">{{ $buku->stok }}</td>
                                            <td class="text-center">{{ $buku->stok_tersedia }}</td>
                                            <td class="text-center">
                                                @if($buku->stok_tersedia <= 0)
                                                    <span class="badge bg-danger">Habis</span>
                                                @elseif($buku->stok_tersedia < 3)
                                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                                @else
                                                    <span class="badge bg-success">Tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @include('partials.laporan-footer')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    /* Tampilan Layar */
    .nav-pills .nav-link { color: #555; border: 1px solid transparent; margin-right: 5px; transition: 0.3s; }
    .nav-pills .nav-link.active { background-color: #0d6efd; color: white; border-color: #0d6efd; }
    .print-only { display: none; }

    /* Tampilan Cetak (Print) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        /* Sembunyikan elemen navigasi dan dashboard */
        .no-print, .nav, .btn, footer, nav, header, aside, .filter-section { 
            display: none !important; 
        }

        /* Tampilkan elemen khusus print */
        .print-only { 
            display: block !important; 
        }

        /* Reset Layout */
        body { 
            background: white !important; 
            color: black !important;
            font-family: "Times New Roman", Times, serif; /* Font formal */
        }
        
        .container, .py-5 { 
            max-width: 100% !important; 
            width: 100% !important; 
            margin: 0 !important; 
            padding: 0 !important; 
        }

        .card { 
            border: none !important; 
            box-shadow: none !important; 
        }

        .card-body { 
            padding: 0 !important; 
        }

        /* Paksa Tab Aktif Saja yang Muncul */
        .tab-content > .tab-pane {
            display: none !important;
        }
        .tab-content > .active {
            display: block !important;
            opacity: 1 !important;
        }

        /* Optimasi Tabel */
        .table { 
            width: 100% !important; 
            border: 1px solid #000 !important;
            margin-top: 10px;
        }
        
        .table th { 
            background-color: #f0f0f0 !important; 
            color: black !important;
            border: 1px solid #000 !important;
            text-transform: uppercase;
            font-size: 11px !important;
            -webkit-print-color-adjust: exact;
        }

        .table td { 
            border: 1px solid #000 !important; 
            font-size: 11px !important;
            padding: 6px !important;
        }

        /* Badge warna saat diprint */
        .badge {
            border: 1px solid #ccc !important;
            color: black !important;
            background: transparent !important;
            padding: 2px 5px !important;
        }

        /* Judul Laporan */
        h3 {
            font-size: 18px !important;
            margin-top: 10px;
        }
        
        hr {
            border-top: 2px solid #000 !important;
            opacity: 1 !important;
        }
    }
</style>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Cek apakah ada parameter active_tab di URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabId = urlParams.get('active_tab');

    if (activeTabId) {
        // Cari button tab berdasarkan target id-nya
        const tabTriggerEl = document.querySelector(`button[data-bs-target="#${activeTabId}"]`);
        if (tabTriggerEl) {
            // Aktifkan tab tersebut menggunakan Bootstrap API
            const tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
            // Update value input hidden agar tetap sinkron
            document.getElementById('active_tab_input').value = activeTabId;
        }
    }

    // 2. Setiap kali user klik tab lain, update nilai input hidden di form
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function (event) {
            // Ambil ID target (misal: pills-denda) tanpa karakter '#'
            const targetId = event.target.getAttribute('data-bs-target').replace('#', '');
            document.getElementById('active_tab_input').value = targetId;
        });
    });
});
</script>
</x-app-layout>