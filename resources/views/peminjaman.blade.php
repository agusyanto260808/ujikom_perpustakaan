<x-app-layout>
    <x-slot name="header">
        {{-- BOOTSTRAP + ICON --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi- arrow-left-right"></i> Kelola Transaksi Peminjaman
            </h5>
            <small class="text-muted">
                Total Transaksi: <strong>{{ $peminjaman->total() }}</strong>
            </small>
        </div>
    </x-slot>

    {{-- Background Putih Polos / Light seperti Dashboard --}}
    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- CARD UTAMA --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                
                {{-- HEADER CARD --}}
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">SEMUA TRANSAKSI</h6>
                        <small class="text-secondary">Monitoring arus peminjaman buku</small>
                    </div>
                </div>

                {{-- TABLE --}}
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
                                {{-- NO --}}
                                <td class="ps-4 small text-muted">
                                    {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                                </td>

                                {{-- USER --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $pinjam->user->name ?? 'User' }}</span>
                                    </div>
                                </td>

                                {{-- BUKU --}}
                                <td>
                                    <div class="fw-semibold text-dark">{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</div>
                                    <span class="badge rounded-pill bg-dark-subtle text-dark small fw-normal">
                                        {{ $pinjam->jumlah }} Buku
                                    </span>
                                </td>

                                {{-- TANGGAL --}}
                                <td class="text-center text-secondary small">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                {{-- JATUH TEMPO --}}
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

                                {{-- AKSI --}}
                              {{-- Ganti bagian AKSI pada tabel peminjaman Anda dengan ini --}}
<td class="text-center pe-4">
    <div class="d-flex justify-content-center gap-2">
        
        @if($statusLower == 'menunggu')
            {{-- HANYA TOMBOL SETUJUI --}}
            <form action="{{ route('peminjaman.update', $pinjam->idpinjam) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Dipinjam">
                <button class="btn btn-sm btn-primary shadow-sm px-3 rounded-pill fw-bold">
                    <i class="bi bi-check-lg"></i> Setujui
                </button>
            </form>

        @elseif(in_array($statusLower, ['kembali', 'selesai', 'proses kembali']))
            {{-- TAMPILAN SELESAI (Untuk status kembali atau sedang diproses kembali) --}}
            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                <i class="bi bi-check-all me-1"></i> Selesai
            </span>

        @else
            {{-- STATUS LAIN (Misal: Sedang Dipinjam) --}}
            <span class="badge rounded-pill bg-light text-secondary border px-3 py-2 fw-normal">
                {{ ucfirst($pinjam->status) }}
            </span>
        @endif

        {{-- TOMBOL HAPUS TETAP ADA --}}
        <form action="{{ route('peminjaman.destroy', $pinjam->idpinjam) }}" method="POST" 
              onsubmit="return confirm('Hapus transaksi ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-light border text-danger shadow-sm">
                <i class="bi bi-trash"></i>
            </button>
        </form>

    </div>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 opacity-25"></i>
                                    <p class="mt-2">Belum ada data transaksi peminjaman</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($peminjaman->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>