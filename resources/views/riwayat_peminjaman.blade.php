<x-app-layout>

    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h5 class="fw-bold text-dark mb-0">
            Log Peminjaman Buku
        </h5>
    </x-slot>

    <div class="bg-light py-5">

        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0">
                    {{ session('success') }}
                </div>
            @endif

            {{-- CARD --}}
            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold text-uppercase mb-0">
                        Daftar Transaksi Peminjaman
                    </h6>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Buku</th>
                                <th class="text-center">Tgl Pinjam</th>
                                <th class="text-center">Jatuh Tempo</th>
                                <th class="text-center">Denda</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse ($peminjaman as $pinjam)

                        @php
                            $status = trim(strtolower($pinjam->status));
                            $jatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->startOfDay();

                            $tglKembaliData = $pinjam->pengembalian ? $pinjam->pengembalian->tanggalkembali : $pinjam->tanggal_kembali;

                            if (in_array($status, ['kembali', 'selesai', 'lunas']) && $tglKembaliData) {
                                $tanggalPatokan = \Carbon\Carbon::parse($tglKembaliData)->startOfDay();
                            } else {
                                $tanggalPatokan = now()->startOfDay();
                            }

                            $tarifDenda = 2000;
                            $totalDenda = 0;
                            $selisihHari = 0;

                            if ($tanggalPatokan->gt($jatuhTempo)) {
                                $selisihHari = $tanggalPatokan->diffInDays($jatuhTempo);
                                $totalDenda = $selisihHari * $tarifDenda;
                            }
                        @endphp

                        <tr>

                            <td class="fw-semibold">
                                {{ $pinjam->user->name ?? 'User' }}
                            </td>

                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $pinjam->buku->judul }}
                                </div>
                                <span class="badge bg-secondary">
                                    {{ $pinjam->jumlah }} Buku
                                </span>
                            </td>

                            <td class="text-center text-muted">
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}
                            </td>

                            <td class="text-center {{ $totalDenda > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo)->format('d/m/Y') }}
                            </td>

                            <td class="text-center">
                                @if($totalDenda == 0)
                                    <span class="badge bg-success">
                                        Aman
                                    </span>
                                @elseif(in_array($status, ['kembali','selesai','lunas']))
                                    <span class="badge bg-success">
                                        Lunas
                                    </span>
                                @else
                                    <div class="text-danger fw-bold">
                                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                    </div>
                                    <small class="text-danger">
                                        Telat {{ $selisihHari }} Hari
                                    </small>
                                @endif
                            </td>

                        {{-- Ganti bagian logika status di dalam @forelse --}}
<td class="text-center">
    @php
        // Pastikan status dibersihkan dari spasi dan huruf kecil
        $status = trim(strtolower($pinjam->status));
        
        // Cek apakah transaksi sudah dianggap lunas/selesai
        $isSelesai = in_array($status, ['kembali', 'selesai', 'lunas']);
    @endphp

    @if($totalDenda > 0 && !$isSelesai)
        {{-- Jika telat dan belum bayar ke petugas --}}
        <button class="btn btn-dark btn-sm w-100" disabled>Bayar Dulu</button>
        
    @elseif($status == 'dipinjam')
        {{-- Jika sedang pinjam dan tidak ada denda (atau denda 0) --}}
        <form action="{{ route('pengembalian.ajukan', $pinjam->idpinjam) }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm w-100">Ajukan Pengembalian</button>
        </form>
        
    @elseif($status == 'proses kembali')
        {{-- Menunggu Admin klik "Terima" --}}
        <span class="badge bg-warning text-dark w-100">Diproses Petugas</span>
        
    @elseif($isSelesai)
        {{-- Berhasil dikonfirmasi oleh Admin --}}
        <span class="badge bg-success w-100">Selesai</span>
        
    @elseif(in_array($status, ['menunggu', 'pending']))
        <span class="badge bg-info text-dark w-100">Menunggu Persetujuan</span>
        
    @else
        <span class="badge bg-secondary w-100">{{ ucfirst($status) }}</span>
    @endif
</td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

                @if($peminjaman->hasPages())
                    <div class="card-footer bg-white">
                        {{ $peminjaman->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>