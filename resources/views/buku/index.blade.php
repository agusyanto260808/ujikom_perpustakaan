<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
        }

        .header-gradient {
            background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
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

        .search-input, .form-select-modern {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .search-input:focus, .form-select-modern:focus {
            box-shadow: 0 0 0 4px rgba(65, 88, 208, 0.1);
            border-color: #4158D0;
        }

        .btn-modern {
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
        }

        .table thead th {
            background-color: #fcfcfd;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody td {
            padding: 18px 20px;
            color: #1e293b;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
            border: none;
        }

        .book-cover {
            width: 45px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>

    {{-- HEADER SECTION --}}
    <div class="header-gradient text-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1 h2">Daftar Koleksi Buku</h1>
                    <p class="opacity-75 mb-0">Total terdapat {{ $buku->total() }} buku dalam perpustakaan</p>
                </div>
                <a href="{{ route('buku.create') }}" class="btn btn-light btn-modern text-primary shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Buku Baru
                </a>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- SEARCH & FILTER SECTION --}}
        <div class="modern-card p-4 mb-4">
            <form method="GET" action="{{ route('buku.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control search-input border-start-0 ps-0"
                               placeholder="Cari judul, penulis, atau ISBN...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-select form-select-modern">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-modern w-100 shadow-sm" style="background: #4158D0; border: none;">
                        Filter data
                    </button>
                </div>
            </form>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 rounded-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TABLE SECTION --}}
        <div class="modern-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Sampul</th>
                            <th>Informasi Buku</th>
                            <th>Penerbit</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                        <tr>
                            <td class="ps-4">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" class="book-cover">
                                @else
                                    <div class="book-cover bg-light d-flex align-items-center justify-content-center border">
                                        <i class="bi bi-journal text-muted opacity-50"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                <div class="small text-muted">{{ $item->penulis }}</div>
                                <code class="small text-primary" style="font-size: 0.7rem;">{{ $item->isbn }}</code>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $item->penerbit }}</span>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $item->tahun }}
                            </td>
                            <td class="text-center">
                                @if($item->stok <= 0)
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Habis</span>
                                @elseif($item->stok <= 5)
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">{{ $item->stok }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">{{ $item->stok }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('buku.show', $item->idbuku) }}"
                                       class="action-btn bg-info-subtle text-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $item->idbuku) }}"
                                       class="action-btn bg-warning-subtle text-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $item->idbuku }}, '{{ $item->judul }}')"
                                            class="action-btn bg-danger-subtle text-danger" title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/searching.svg" alt="Empty" style="width: 150px;" class="mb-3">
                                <p class="text-muted">Oops! Tidak ditemukan data buku yang Anda cari.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($buku->hasPages())
                <div class="px-4 py-4 border-top">
                    {{ $buku->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id, judul) {
            Swal.fire({
                title: 'Hapus Buku?',
                text: "Buku '" + judul + "' akan dihapus permanen dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4158D0',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'modern-card',
                    confirmButton: 'btn-modern btn btn-primary',
                    cancelButton: 'btn-modern btn btn-light'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = "{{ url('buku') }}/" + id;
                    form.method = 'POST';
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>
</x-app-layout>