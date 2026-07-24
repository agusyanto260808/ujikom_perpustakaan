<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
        }
        .page-header {
            padding: 2rem 0;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }
        .card-main {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background: #ffffff;
        }
        .table thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .table tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }
        .book-title {
            font-weight: 600;
            color: #0f172a;
            display: block;
        }
        .user-name {
            font-weight: 700;
            color: #4f46e5; /* Indigo */
        }
        .badge-modern {
            padding: 0.5em 1em;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.75rem;
        }
        .btn-indigo {
            background-color: #4f46e5;
            color: white;
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.2s;
            border: none;
        }
        .btn-indigo:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            color: white;
        }
    </style>

    <div class="page-header">
        {{-- <div class="container">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-indigo text-white p-3 rounded-4 shadow-sm" style="background: #4f46e5;">
                    <i class="bi bi-journal-text fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Log Peminjaman</h4>
                    <p class="text-muted small mb-0">Pantau dan kelola semua transaksi buku Anda</p>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="container pb-5 mt-5">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="card-main">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Informasi Peminjam</th>
                            <th>Buku & Jumlah</th>
                            <th class="text-center">Tgl Pinjam</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Denda</th>
                            <th class="text-end">Aksi / Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $pinjam)
   @php
    $status = trim(strtolower($pinjam->status));
    $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();
    $isSelesai = in_array($status, ['kembali', 'selesai', 'lunas']);
    
    // Ambil denda langsung dari kolom di tabel peminjaman
    $totalDenda = $pinjam->denda ?? 0; 
    
    $selisihHari = 0;
    if (!$isSelesai) {
        // Jika masih dipinjam, hitung denda berjalan (Real-time) untuk tampilan saja
        $hariIni = now()->startOfDay();
        if ($hariIni->gt($jatuhTempo)) {
            $selisihHari = $hariIni->diffInDays($jatuhTempo);
            $totalDenda = $selisihHari * 2000;
        }
    } else {
        // Jika sudah selesai, kita asumsikan selisih hari dihitung dari nominal denda
        // atau Anda bisa menambahkan kolom 'hari_terlambat' di tabel peminjaman jika ingin lebih akurat
        $selisihHari = $totalDenda / 2000;
    }
@endphp
                        <tr>
                            <td>
                                <div class="user-name">{{ $pinjam->user->name ?? 'User' }}</div>
                                <div class="text-muted small">ID: #TRX-{{ $pinjam->idpinjam }}</div>
                            </td>
                            <td>
                                <span class="book-title">{{ $pinjam->buku->judul }}</span>
                                <span class="badge bg-light text-dark border p-1 px-2 mt-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-book me-1"></i>{{ $pinjam->jumlah }} Buku
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="small fw-medium text-secondary">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M, Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="small {{ $totalDenda > 0 && !$isSelesai ? 'text-danger fw-bold' : 'text-secondary' }}">
                                    {{ $jatuhTempo->format('d M, Y') }}
                                </span>
                            </td>
                            <td class="text-center">
    @if($totalDenda == 0)
        <span class="text-success small fw-semibold">
            <i class="bi bi-check2-all me-1"></i>Tidak ada
        </span>
    @elseif($isSelesai)
        {{-- Menggunakan abs() agar jika di DB negatif tetap tampil positif --}}
        <div class="text-success fw-bold mb-0">
            Rp {{ number_format(abs($totalDenda), 0, ',', '.') }}
        </div>
        <span class="badge bg-success-subtle text-success badge-modern">Lunas</span>
    @else
        {{-- Menggunakan abs() untuk menghilangkan tanda minus --}}
        <div class="text-danger fw-bold mb-0">
            Rp {{ number_format(abs($totalDenda), 0, ',', '.') }}
        </div>
        <div class="text-danger opacity-75" style="font-size: 0.7rem;">
            Telat {{ abs($selisihHari) }} Hari
        </div>
    @endif
</td>
                            <td class="text-end">
                                @if($status == 'dipinjam')
                                    <form action="{{ route('pengembalian.ajukan', $pinjam->idpinjam) }}" method="POST" class="form-kembalikan">
            @csrf
            <button type="button" class="btn btn-indigo shadow-sm btn-submit-kembali">
                Ajukan Pengembalian <i class="bi bi-arrow-right-short ms-1"></i>
            </button>
        </form>
                                @elseif($status == 'proses kembali')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle badge-modern">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($isSelesai)
                                    <span class="badge bg-indigo-subtle text-primary border border-primary-subtle badge-modern" style="background: #eef2ff; color: #4f46e5 !important;">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary badge-modern">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3" alt="Empty">
                                <p class="text-muted">Belum ada riwayat transaksi peminjaman.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($peminjaman->hasPages())
                <div class="p-4 border-top bg-light-subtle rounded-bottom-4">
                    {{ $peminjaman->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-submit-kembali').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.form-kembalikan');
            
            Swal.fire({
                title: 'Yakin ingin mengembalikan?',
                text: "Pastikan barang dalam kondisi baik saat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Warna indigo sesuai btn Anda
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form jika user klik 'Ya'
                }
            });
        });
    });
</script>