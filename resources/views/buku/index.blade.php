<x-app-layout>
    <x-slot name="header">
        {{-- BOOTSTRAP + ICON --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-book"></i> Daftar Buku
            </h5>
            <small class="text-muted">
                Total: <strong>{{ $buku->total() }}</strong>
            </small>
        </div>
    </x-slot>

    {{-- Ganti BG di sini menjadi bg-light (Abu muda sangat terang) atau bg-white --}}
    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 mb-4">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- SEARCH --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('buku.index') }}" class="row g-3">
                        
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search"
                                    value="{{ request('search') }}"
                                    class="form-control border-0 bg-light"
                                    placeholder="Cari judul, penulis, atau ISBN...">
                            </div>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary w-100 fw-bold">
                                Cari
                            </button>

                            @if(request('search'))
                            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Data Koleksi Buku</h6>
                        <small class="text-secondary">Kelola data buku perpustakaan</small>
                    </div>

                    <a href="{{ route('buku.create') }}" class="btn btn-success btn-sm px-3 fw-bold shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
                    </a>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3 small text-uppercase">ID</th>
                                <th class="py-3 small text-uppercase">Sampul</th>
                                <th class="py-3 small text-uppercase">Informasi Buku</th>
                                <th class="py-3 small text-uppercase">Penerbit</th>
                                <th class="py-3 small text-uppercase text-center">Tahun</th>
                                <th class="py-3 small text-uppercase text-center">Stok</th>
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @forelse ($buku as $item)
                            <tr>
                                <td class="ps-4 small text-muted">#{{ $item->idbuku }}</td>
                                <td>
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                            style="width: 45px; height: 60px; object-fit: cover;" 
                                            class="rounded shadow-sm">
                                    @else
                                        <div class="bg-light text-center rounded d-flex align-items-center justify-content-center border"
                                             style="width:45px;height:60px;">
                                            <i class="bi bi-image text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                    <div class="small text-secondary">{{ $item->penulis }} | <span class="text-muted">{{ $item->isbn }}</span></div>
                                </td>
                                <td class="text-secondary small">{{ $item->penerbit }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-normal">
                                        {{ $item->tahun }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->stok <= 0)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Habis</span>
                                    @elseif($item->stok <= 5)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3">{{ $item->stok }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3">{{ $item->stok }}</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('buku.edit', $item->idbuku) }}"
                                           class="btn btn-sm btn-light border text-warning shadow-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $item->idbuku }}, '{{ $item->judul }}')"
                                                class="btn btn-sm btn-light border text-danger shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 opacity-25"></i><br>
                                    <p class="mt-2">Belum ada data buku yang tersedia</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($buku->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $buku->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>