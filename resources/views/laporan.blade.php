<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
        }

        /* Tema Gradient Header (Serupa dengan halaman Buku/Pengembalian) */
        .header-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
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

        /* Nav Pills Modern */
        .nav-pills-modern {
            background: #fff;
            padding: 8px;
            border-radius: 15px;
            display: inline-flex;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .nav-pills-modern .nav-link {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            color: #64748b;
            transition: 0.3s;
        }

        .nav-pills-modern .nav-link.active {
            background: #1e293b;
            color: #fff;
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        }

        /* Form Styling */
        .form-select-modern {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
        }

        .btn-modern {
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-modern:hover { transform: translateY(-2px); }

        .print-only { display: none; }

        @media print {
        /* Memaksa browser mencetak warna latar belakang dan grafik */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Pengaturan Ukuran Kertas A4 */
        @page {
            size: A4;
            margin: 1.5cm;
        }

        /* Sembunyikan elemen UI yang tidak perlu */
        .no-print, 
        .nav-pills-modern, 
        .header-gradient, 
        button, 
        form, 
        nav {
            display: none !important;
        }

        /* Tampilkan elemen khusus cetak */
        .print-only {
            display: block !important;
        }

        body {
            background: white !important;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            color: #000;
        }

        /* Reset Card agar tidak melayang (no shadow) */
        .modern-card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Styling Tabel ala Dokumen Resmi */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 20px;
        }

        .table th {
            background-color: #f8f9fa !important; /* Abu-abu muda untuk header */
            color: #000 !important;
            border: 1px solid #333 !important;
            padding: 8px !important;
            text-transform: uppercase;
            font-size: 10pt;
        }

        .table td {
            border: 1px solid #333 !important;
            padding: 8px !important;
            vertical-align: middle !important;
        }

        /* Menghilangkan badge yang terlalu mencolok saat diprint */
        .badge {
            border: 1px solid #ccc !important;
            background: transparent !important;
            color: #000 !important;
            padding: 2px 5px !important;
        }

        /* Force tab content yang sedang aktif untuk memenuhi halaman */
        .tab-pane {
            display: none !important;
            opacity: 1 !important;
        }
        .tab-pane.active {
            display: block !important;
        }
    }
    </style>

    {{-- HEADER SECTION --}}
    <div class="header-gradient text-white no-print">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1 h2">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Pusat Laporan
                    </h1>
                    <p class="opacity-75 mb-0">Periode Laporan: {{ $nama_bulan }} {{ $tahun }}</p>
                </div>
                <div class="nav-pills-modern shadow-sm">
                    <ul class="nav nav-pills border-0" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-pinjam">Peminjaman</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-kembali">Pengembalian</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-buku">Inventaris</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5 mt-4">
        {{-- KOP SURAT (HANYA MUNCUL SAAT PRINT) --}}
       {{-- KOP SURAT (HANYA MUNCUL SAAT PRINT) --}}
<div class="print-only mb-4">
    <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 10px;">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="{{ asset('storage/logo.png') }}" alt="Logo" style="width: 80px;">
            </td>
            <td style="width: 85%; text-align: center;">
                <h3 style="margin: 0; text-transform: uppercase; font-weight: bold;">Perpustakaan Terpadu Digital</h3>
                <p style="margin: 0; font-size: 10pt;">Jl. Julaeni, Kota Banjar, Jawa Barat 46341</p>
                <p style="margin: 0; font-size: 10pt;">Email: smkn3banjar@ymail.com | Telp: (0265) 2734141</p>
            </td>
        </tr>
    </table>
</div>

        {{-- FILTER SECTION --}}
        <div class="modern-card p-4 mb-4 no-print shadow-sm">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                <input type="hidden" name="active_tab" id="active_tab_input" value="{{ request('active_tab', 'pills-pinjam') }}">
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary mb-2">Filter Bulan</label>
                    <select name="bulan" class="form-select form-select-modern shadow-none">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ (int)$bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary mb-2">Filter Tahun</label>
                    <select name="tahun" class="form-select form-select-modern shadow-none">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ (int)$tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-modern btn-dark flex-grow-1 shadow-sm">
                        <i class="bi bi-filter me-1"></i> Terapkan
                    </button>
                    <button type="button" onclick="window.print()" class="btn btn-modern btn-primary shadow-sm px-4">
                        <i class="bi bi-printer me-1"></i> Cetak Laporan
                    </button>
                </div>
            </form>
        </div>

        <div class="tab-content" id="pills-tabContent">
            
            {{-- 1. TAB PEMINJAMAN --}}
            <div class="tab-pane fade show active" id="pills-pinjam">
                <div class="modern-card">
                    <div class="card-body p-lg-5">
                        <div class="text-center mb-5">
                            <h4 class="fw-bold text-uppercase mb-1">Laporan Peminjaman</h4>
                            <p class="text-muted small">Periode {{ $nama_bulan }} {{ $tahun }}</p>
                            <div class="bg-primary mx-auto" style="height: 3px; width: 40px;"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0">
                                <thead class="bg-light">
                                    <tr class="text-uppercase small fw-bold">
                                        <th class="ps-4">No</th>
                                        <th>Nama Peminjam</th>
                                        <th>Judul Buku</th>
                                        <th class="text-center">Tgl Pinjam</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laporan as $item)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $item->user->name }}</td>
                                        <td>{{ $item->buku->judul }}</td>
                                        <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @if($item->status == 'Menunggu')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">Menunggu</span>
                                            @elseif($item->status == 'Dipinjam')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data transaksi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @include('partials.laporan-footer')
                    </div>
                </div>
            </div>

            {{-- 2. TAB PENGEMBALIAN --}}
            <div class="tab-pane fade" id="pills-kembali">
                <div class="modern-card">
                    <div class="card-body p-lg-5">
                        <div class="text-center mb-5">
                            <h4 class="fw-bold text-uppercase mb-1">Laporan Pengembalian</h4>
                            <p class="text-muted small">Rekapitulasi Denda & Pengembalian Periode {{ $nama_bulan }} {{ $tahun }}</p>
                            <div class="bg-success mx-auto" style="height: 3px; width: 40px;"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-success bg-opacity-10 text-success">
                                    <tr class="text-uppercase small fw-bold">
                                        <th class="ps-4">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Buku</th>
                                        <th class="text-center">Jml</th>
                                        <th class="text-center">Tgl Kembali</th>
                                        <th class="text-end">Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotalDenda = 0; $totalBuku = 0; @endphp
                                    @forelse($laporanKembali as $item)
                                        @php 
                                            $nominal = $item->pengembalian->denda->jumlah ?? 0;
                                            $grandTotalDenda += $nominal;
                                            $totalBuku += $item->jumlah;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                            <td class="fw-bold">{{ $item->user->name }}</td>
                                            <td>{{ $item->buku->judul }}</td>
                                            <td class="text-center">{{ $item->jumlah }}</td>
                                            <td class="text-center">{{ $item->pengembalian ? \Carbon\Carbon::parse($item->pengembalian->tanggalkembali)->format('d/m/Y') : '-' }}</td>
                                            <td class="text-end fw-bold {{ $nominal > 0 ? 'text-danger' : 'text-success' }}">
                                                Rp {{ number_format($nominal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-5">Kosong.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td colspan="3" class="text-end text-uppercase small">Total Buku Kembali:</td>
                                        <td class="text-center">{{ $totalBuku }} Eks</td>
                                        <td class="text-end text-uppercase small">Total Denda:</td>
                                        <td class="text-end text-danger">Rp {{ number_format($grandTotalDenda, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @include('partials.laporan-footer')
                    </div>
                </div>
            </div>

            {{-- 3. TAB INVENTARIS --}}
            <div class="tab-pane fade" id="pills-buku">
                <div class="modern-card">
                    <div class="card-body p-lg-5">
                        <div class="text-center mb-5">
                            <h4 class="fw-bold text-uppercase mb-1">Inventaris Buku</h4>
                            <p class="text-muted small">Update Stok Koleksi Terkini</p>
                            <div class="bg-dark mx-auto" style="height: 3px; width: 40px;"></div>
                        </div>
                        <table class="table table-hover align-middle border">
                            <thead class="bg-dark text-white">
                                <tr class="text-uppercase small">
                                    <th class="ps-3">No</th>
                                    <th>Judul Buku</th>
                                    <th class="text-center">Total Stok</th>
                                    <th class="text-center">Tersedia</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($buku_all as $b)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $b->judul }}</td>
                                    <td class="text-center">{{ $b->stok }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $b->stok_tersedia }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $b->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                            {{ $b->stok_tersedia > 0 ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('partials.laporan-footer')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic tab persistent
            const urlParams = new URLSearchParams(window.location.search);
            const activeTabId = urlParams.get('active_tab');
            if (activeTabId) {
                const tabTriggerEl = document.querySelector(`button[data-bs-target="#${activeTabId}"]`);
                if (tabTriggerEl) {
                    const tab = new bootstrap.Tab(tabTriggerEl);
                    tab.show();
                    document.getElementById('active_tab_input').value = activeTabId;
                }
            }
            document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
                button.addEventListener('shown.bs.tab', (e) => {
                    const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                    document.getElementById('active_tab_input').value = targetId;
                });
            });
        });
    </script>
</x-app-layout>