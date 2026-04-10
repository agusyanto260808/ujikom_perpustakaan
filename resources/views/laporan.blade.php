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
    @forelse($laporan as $item)
    <tr class="text-center">
        <td>{{ $loop->iteration }}</td>
        <td class="text-start fw-bold">{{ $item->user->name }}</td>
        <td class="text-start">{{ $item->buku->judul }}</td>
        <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
        <td>
            @if($item->status == 'Menunggu')
                <span class="badge border text-warning border-warning">Menunggu</span>
            @elseif($item->status == 'Dipinjam')
                <span class="badge border text-primary border-primary">Sedang Dipinjam</span>
            @else
                <span class="badge border text-success border-success">Selesai</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center py-4 text-muted">Tidak ada transaksi peminjaman periode ini.</td>
    </tr>
    @endforelse
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
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="bg-success bg-opacity-10 text-success">
                        <tr class="text-center small fw-bold">
                            <th>No</th>
                            <th class="text-start">Nama Siswa</th>
                            <th class="text-start">Buku</th>
                            <th>Jml</th> {{-- Kolom Jumlah --}}
                            <th>Tgl Kembali</th>
                            <th>Terlambat</th>
                            <th class="text-end">Nominal Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grandTotalDenda = 0; 
                            $totalBukuBerhasilKembali = 0; 
                        @endphp
                        
                        @forelse($laporanKembali as $item)
                            @php 
                                $tglKembali = $item->pengembalian ? \Carbon\Carbon::parse($item->pengembalian->tanggalkembali) : null;
                                $dendaObj = $item->pengembalian ? $item->pengembalian->denda : null;
                                $nominal = $dendaObj ? (int)$dendaObj->jumlah : 0;
                                
                                $grandTotalDenda += $nominal;
                                // Menjumlahkan quantity buku
                                $totalBukuBerhasilKembali += $item->jumlah; 
                            @endphp
                            
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start fw-bold">{{ $item->user->name }}</td>
                                <td class="text-start">{{ $item->buku->judul }}</td>
                                <td class="fw-bold">{{ $item->jumlah }}</td> {{-- Menampilkan jumlah per baris --}}
                                <td class="fw-bold">{{ $tglKembali ? $tglKembali->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($nominal > 0)
                                        <span class="text-danger fw-bold">{{ $dendaObj->hari_terlambat }} Hari</span>
                                    @else
                                        <span class="text-success small">Tepat Waktu</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    Rp {{ number_format($nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada pengembalian pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <tfoot class="table-light">
                        {{-- Baris Total Buku --}}
                        <tr class="fw-bold text-dark">
                            <td colspan="3" class="text-end text-uppercase">Total Koleksi Buku Kembali:</td>
                            <td class="text-center text-primary" style="font-size: 1rem;">
                                {{ $totalBukuBerhasilKembali }} Eks
                            </td>
                            <td colspan="3"></td>
                        </tr>

                        {{-- Baris Total Denda --}}
                        @if($grandTotalDenda > 0)
                        <tr class="fw-bold text-dark">
                            <td colspan="6" class="text-end text-uppercase">Total Pendapatan Denda:</td>
                            <td class="text-end text-danger" style="font-size: 1rem;">
                                Rp {{ number_format($grandTotalDenda, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
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
        
        {{-- Total semua buku yang dimiliki --}}
        <td class="text-center">{{ $buku->stok }}</td>

        {{-- Sisa yang ada di rak --}}
        <td class="text-center fw-bold text-primary">
            {{ $buku->stok_tersedia }}
        </td>
        
        <td class="text-center">
            @if($buku->stok_tersedia <= 0)
                <span class="badge bg-danger">Habis</span>
            @elseif($buku->stok_tersedia < 3)
                <span class="badge bg-warning text-dark">Kritis</span>
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
            border: 1px solid #dee2e6 !important;
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
            background-color: #f8f9fa !important;
            color: black !important;
            border: 1px solid #000 !important;
            text-transform: uppercase;
            font-size: 11px !important;
            print-color-adjust: exact;
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
        a[href]:after {
        content: none !important;
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