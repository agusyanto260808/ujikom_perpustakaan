<x-app-layout>
    <x-slot name="header">
        {{-- Import CSS yang sama dengan peminjaman --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0 mt-5">
                <i class="bi bi-cash-stack"></i> Riwayat Denda Keterlambatan
            </h2>
            <small class="text-muted mt-5">
                Total Riwayat: <strong>{{ $dataDenda->total() }}</strong>
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

            {{-- STATISTIK PENDAPATAN --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-3 bg-primary text-white">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="small fw-bold mb-0 text-white-50 text-uppercase">Total Pendapatan</p>
                                    <h4 class="fw-black mb-0">Rp {{ number_format($dataDenda->sum('jumlah'), 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                    <i class="bi bi-wallet2 fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD UTAMA --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">DATA DENDA TERKUMPUL</h6>
                    <small class="text-secondary">Laporan nominal denda yang telah dilunasi</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3 small text-uppercase">No</th>
                                <th class="py-3 small text-uppercase">Peminjam</th>
                                <th class="py-3 small text-uppercase">Buku</th>
                                <th class="py-3 small text-uppercase text-center">Keterlambatan</th>
                                <th class="py-3 small text-uppercase text-end">Total Denda</th>
                                <th class="py-3 small text-uppercase text-center">Status</th>
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @forelse ($dataDenda as $item)
                            <tr>
                                <td class="ps-4 small text-muted">
                                    {{ ($dataDenda->currentPage() - 1) * $dataDenda->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-2">
                                            <i class="bi bi-person text-danger"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $item->pengembalian->peminjaman->user->name ?? 'User' }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->pengembalian->peminjaman->buku->judul ?? 'Buku Dihapus' }}</div>
                                    <small class="text-muted italic">ID Pengembalian: #{{ $item->idpengembalian }}</small>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-danger-subtle text-danger px-3 fw-bold">
                                        {{ $item->hari_terlambat }} Hari
                                    </span>
                                </td>

                                <td class="text-end fw-bold text-danger">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
    @if($item->status == 'Lunas')
        <span class="badge rounded-pill bg-success-subtle text-success px-3 border border-success-subtle">
            <i class="bi bi-check2-circle me-1"></i> Lunas
        </span>
    @else
        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 border border-warning-subtle">
            <i class="bi bi-clock-history me-1"></i> Belum Lunas
        </span>
    @endif
</td>

<td class="text-center pe-4">
    <div class="d-flex justify-content-center gap-2">
        @if($item->status == 'Belum Lunas')
            <form action="{{ route('denda.konfirmasi', $item->iddenda) }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-primary shadow-sm">
                    <i class="bi bi-cash me-1"></i> Bayar
                </button>
            </form>
        @endif
        
        <form action="{{ route('denda.destroy', $item->iddenda) }}" method="POST" onsubmit="return confirm('Hapus riwayat denda?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-light border text-danger">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 opacity-25"></i>
                                    <p class="mt-2">Tidak ada riwayat denda ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($dataDenda->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $dataDenda->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>