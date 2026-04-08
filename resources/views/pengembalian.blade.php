<x-app-layout>
    <x-slot name="header">
        {{-- BOOTSTRAP + ICON --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-in-down"></i> Konfirmasi Pengembalian Buku
            </h5>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">
                Antrean: {{ $pengembalian->total() }}
            </span>
        </div>
    </x-slot>

    {{-- Container dengan background abu muda bersih --}}
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

                {{-- HEADER --}}
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">DAFTAR PENGAJUAN</h6>
                        <small class="text-secondary">Verifikasi pengembalian buku dan denda</small>
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
                                <th class="py-3 small text-uppercase text-center">Jatuh Tempo</th>
                                <th class="py-3 small text-uppercase text-center">Status Denda</th>
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @forelse ($pengembalian as $item)

                            @php
                                $tglJatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo);
                                $hariIni = now()->startOfDay();
                                $selisihHari = $tglJatuhTempo->diffInDays($hariIni, false);
                                $tarifDenda = 2000;
                                $totalDenda = $selisihHari > 0 ? $selisihHari * $tarifDenda : 0;
                            @endphp

                            <tr>
                                {{-- NO --}}
                                <td class="ps-4 small text-muted">
                                    {{ ($pengembalian->currentPage() - 1) * $pengembalian->perPage() + $loop->iteration }}
                                </td>

                                {{-- USER --}}
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">ID Pinjam: #{{ $item->idpinjam }}</small>
                                </td>

                                {{-- BUKU --}}
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->buku->judul ?? 'Buku tidak tersedia' }}</div>
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary small fw-normal">
                                        {{ $item->jumlah }} Buku
                                    </span>
                                </td>

                                {{-- JATUH TEMPO --}}
                                <td class="text-center">
                                    @if($selisihHari > 0)
                                        <span class="text-danger fw-bold small">
                                            <i class="bi bi-calendar-x me-1"></i>{{ $tglJatuhTempo->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-secondary small">
                                            {{ $tglJatuhTempo->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- DENDA --}}
                                <td class="text-center">
                                    @if($totalDenda > 0)
                                        <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                            <span class="fw-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                            <br>
                                            <small style="font-size: 0.7rem;">Telat {{ $selisihHari }} Hari</small>
                                        </div>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-3 rounded-pill fw-normal">
                                            Bebas Denda
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                              <td class="text-center pe-4">
    <div class="d-flex justify-content-center gap-2">
        @php 
            // Ambil status, kecilkan semua, dan hapus spasi liar
            $statusSekarang = trim(strtolower($item->status)); 
        @endphp

        @if($statusSekarang == 'proses kembali')
            <form action="{{ route('pengembalian.store', $item->idpinjam) }}" method="POST">
                @csrf
                <input type="hidden" name="nominal_denda" value="{{ $totalDenda }}">
                <button type="submit" class="btn btn-sm btn-success shadow-sm px-3 rounded-pill fw-bold">
                    <i class="bi bi-check-lg"></i> Terima Buku
                </button>
            </form>

        {{-- Gunakan in_array untuk jaga-jaga jika ada status 'kembali' atau 'selesai' --}}
        @elseif(in_array($statusSekarang, ['kembali', 'selesai']))
            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                <i class="bi bi-check-all me-1"></i> Selesai
            </span>
        @else
            <span class="badge rounded-pill bg-light text-secondary border px-3 py-2 fw-normal">
                {{ ucfirst($item->status) }}
            </span>
        @endif
        
        <form action="{{ route('peminjaman.destroy', $item->idpinjam) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>   
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard-check fs-1 opacity-25"></i>
                                    <p class="mt-2">Tidak ada pengajuan pengembalian yang perlu diproses</p>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($pengembalian->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $pengembalian->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>