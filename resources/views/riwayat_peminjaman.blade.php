<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Custom CSS untuk merampingkan tampilan */
            .table-custom th { font-size: 0.85rem; letter-spacing: 0.05em; color: #6c757d; }
            .badge-status { min-width: 100px; padding: 0.5em 0.8em; font-weight: 500; }
            .btn-action { font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 8px; }
            .text-small { font-size: 0.75rem; }
        </style>
        <h5 class="fw-bold text-dark mb-0">Log Peminjaman Buku</h5>
    </x-slot>

    <div class="bg-light py-4">
        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold text-secondary mb-0">DAFTAR TRANSAKSI</h6>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0 table-custom">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">NAMA</th>
                                <th>BUKU</th>
                                <th class="text-center">PINJAM</th>
                                <th class="text-center">JATUH TEMPO</th>
                                <th class="text-center">DENDA</th>
                                <th class="text-center pe-4">STATUS</th>
                            </tr>
                        </thead>

                       <tbody>
@forelse ($peminjaman as $pinjam)
    @php
        $status = trim(strtolower($pinjam->status));
        $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();
        
        // Identifikasi apakah transaksi sudah selesai
        $isSelesai = in_array($status, ['kembali', 'selesai', 'lunas']);
        
        $totalDenda = 0;
        $selisihHari = 0;

        if ($isSelesai && $pinjam->pengembalian) {
            // Ambil data denda dari tabel denda melalui DB (karena relasi hasOneThrough mungkin belum optimal)
            $dataDenda = DB::table('denda')->where('idpengembalian', $pinjam->pengembalian->idkembali)->first();
            $totalDenda = $dataDenda ? $dataDenda->jumlah : 0;
            $selisihHari = $dataDenda ? $dataDenda->hari_terlambat : 0;
        } else {
            // Hitung estimasi denda berjalan jika status masih dipinjam/proses
            $tanggalPatokan = now()->startOfDay();
            if ($tanggalPatokan->gt($jatuhTempo)) {
                $selisihHari = $tanggalPatokan->diffInDays($jatuhTempo);
                $totalDenda = $selisihHari * 2000;
            }
        }
    @endphp {{-- Pastikan penutup @php ada di sini --}}

    <tr>
        <td class="ps-4 fw-bold text-dark">
            {{ $pinjam->user->name ?? 'User' }}
        </td>

        <td>
            <div class="fw-semibold">{{ $pinjam->buku->judul }}</div>
            <span class="text-muted text-small">{{ $pinjam->jumlah }} Buku</span>
        </td>

        <td class="text-center text-muted text-small">
            {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
        </td>

        <td class="text-center">
            {{-- SEKARANG $totalDenda SUDAH TERDEFINISI DI SINI --}}
            <span class="text-small {{ $totalDenda > 0 && !$isSelesai ? 'text-danger fw-bold' : 'text-muted' }}">
                {{ $jatuhTempo->format('d M Y') }}
            </span>
        </td>

        <td class="text-center">
            @if($totalDenda == 0)
                <span class="text-success text-small">Aman</span>
            @elseif($isSelesai)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2">Lunas</span>
            @else
                <div class="text-danger fw-bold mb-0" style="font-size: 0.85rem;">
                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </div>
                <div class="text-danger text-small opacity-75">Telat {{ $selisihHari }} Hari</div>
            @endif
        </td>

        <td class="text-center pe-4" style="width: 180px;">
            @if($status == 'dipinjam')
                <form action="{{ route('pengembalian.ajukan', $pinjam->idpinjam) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-action w-100 shadow-sm">Ajukan Kembali</button>
                </form>
            @elseif($status == 'proses kembali')
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle badge-status rounded-pill">Diproses Petugas</span>
            @elseif($isSelesai)
                <span class="badge bg-success-subtle text-success border border-success-subtle badge-status rounded-pill">Selesai</span>
            @else
                <span class="badge bg-secondary badge-status rounded-pill text-small">{{ ucfirst($status) }}</span>
            @endif
        </td>
    </tr>
@empty
    @endforelse
</tbody>
                    </table>
                </div>

                @if($peminjaman->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>