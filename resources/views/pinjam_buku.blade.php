<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('katalog_buku.index') }}" class="text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $item->judul }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/default.png') }}" 
                     class="card-img-top rounded shadow" alt="{{ $item->judul }}">
            </div>
        </div>

        <div class="col-md-5">
            <p class="text-primary fw-bold text-uppercase small mb-1">{{ $item->penulis }}</p>
            <h1 class="display-5 fw-bold mb-4">{{ $item->judul }}</h1>

            <h5 class="fw-bold border-bottom pb-2 mb-3">Detail Buku</h5>
            <table class="table table-borderless smal">
                <tr>
                    <td class="text-muted ps-0">Penerbit</td>
                    <td class="fw-bold text-end">{{ $item->penerbit }}</td>
                </tr>
                <tr>
                    <td class="text-muted ps-0">Tahun Terbit</td>
                    <td class="fw-bold text-end">{{ $item->tahun_terbit ?? $item->tahun }}</td>
                </tr>
                <tr>
                    <td class="text-muted ps-0">Kategori</td>
                    <td class="fw-bold text-end text-primary">Edukasi & Referensi</td>
                </tr>
            </table>

            <div class="mt-4">
                <h5 class="fw-bold">Sinopsis / Deskripsi</h5>
                <p class="text-secondary leading-relaxed">
                    Buku <strong>{{ $item->judul }}</strong> merupakan karya literatur berkualitas yang disusun oleh <strong>{{ $item->penulis }}</strong>. 
                    Koleksi ini tersedia untuk mendukung kegiatan belajar mengajar di lingkungan sekolah.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body bg-light border-0 rounded-4 shadow-sm sticky-top" style="top: 20px;">
                <label class="text-muted fw-bold small mb-2 text-uppercase">Ketersediaan</label>
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle me-2 {{ $item->stok > 0 ? 'bg-success' : 'bg-danger' }}" style="width: 12px; height: 12px;"></div>
                    <span class="fw-bold {{ $item->stok > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $item->stok > 0 ? 'Stok: ' . $item->stok : 'Stok Kosong' }}
                    </span>
                </div>

                @if($item->stok > 0)
                    <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Form Peminjaman</h5>
        
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            {{-- ID Buku tersembunyi --}}
            <input type="hidden" name="idbuku" value="{{ $item->idbuku }}">

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" 
                       class="form-control" 
                       value="{{ now()->addDays(7)->format('Y-m-d') }}" 
                       required>
            </div>

            <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">Jumlah Pinjam:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeValue(-1)">-</button>
                                <input type="number" name="jumlah" id="qty" value="1" min="1" max="{{ $item->stok }}" 
                                       class="form-control text-center fw-bold" oninput="validateInput(this)">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeValue(1)">+</button>
                            </div>
                            <small id="error-msg" class="text-danger d-none">Melebihi stok!</small>
                        </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                KONFIRMASI PINJAM
            </button>
        </form>
    </div>
</div>
                        @csrf
                        <input type="hidden" name="idbuku" value="{{ $item->idbuku }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Estimasi Tanggal Kembali:</label>
                            <input type="date" 
                                   name="tanggal_kembali" 
                                   value="{{ now()->addDays(7)->format('Y-m-d') }}"
                                   min="{{ now()->addDays(1)->format('Y-m-d') }}"
                                   class="form-control rounded-3" required>
                        </div>

                       

                        <form action="{{ route('peminjaman.store') }}" method="POST">
    @csrf
</form>
                    </form>
                @else
                    <button disabled class="btn btn-secondary w-100 py-3 fw-bold rounded-3 mb-2">STOK HABIS</button>
                @endif

                <button class="btn btn-outline-danger w-100 py-2 fw-bold rounded-3">
                    ❤ Favorit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function changeValue(delta) {
        const input = document.getElementById('qty');
        const max = parseInt(input.getAttribute('max'));
        let current = parseInt(input.value) || 1;
        let newValue = current + delta;
        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
        }
    }

    function validateInput(input) {
        const max = parseInt(input.getAttribute('max'));
        const errorMsg = document.getElementById('error-msg');
        if (parseInt(input.value) > max) {
            input.value = max;
            errorMsg.classList.remove('d-none');
        } else {
            errorMsg.classList.add('d-none');
        }
    }
</script>