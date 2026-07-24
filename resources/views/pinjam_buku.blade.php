<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .book-cover { transition: transform 0.3s ease; }
    .book-cover:hover { transform: scale(1.02); }
    .sticky-panel { top: 2rem; }
    .table-detail td { padding: 0.75rem 0; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('katalog_buku.index') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">{{ $buku->judul }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-panel">
                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                     class="card-img-top rounded-3 book-cover" alt="{{ $buku->judul }}">
            </div>
        </div>

        <div class="col-lg-5">
            <div class="ps-lg-3">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-2 rounded-pill uppercase small fw-bold">
                    {{ $buku->penulis }}
                </span>
                <h1 class="display-6 fw-bold mb-4">{{ $buku->judul }}</h1>

                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Detail Buku
                </h5>
                <table class="table table-borderless table-detail border-bottom mb-4">
                    <tr>
                        <td class="text-muted">Penerbit</td>
                        <td class="fw-bold text-end">{{ $buku->penerbit }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tahun Terbit</td>
                        <td class="fw-bold text-end">{{ $buku->tahun_terbit ?? $buku->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori</td>
                        <td class="fw-bold text-end"><span class="text-primary">Edukasi & Referensi</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Stok</td>
                        <td class="text-end">
                            @if($buku->stok_tersedia > 0)
                                <span class="badge bg-success-soft text-success border border-success-subtle px-3 py-2">
                                    <i class="bi bi-check2-circle"></i> Tersedia: {{ $buku->stok_tersedia }}
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                    <i class="bi bi-x-circle"></i> Habis (Dipinjam)
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="mt-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi- journeymap me-2 text-primary"></i> Sinopsis</h5>
                    <p class="text-secondary lh-lg">
                        Buku <strong>{{ $buku->judul }}</strong> merupakan karya literatur berkualitas yang disusun oleh <strong>{{ $buku->penulis }}</strong>. 
                        Koleksi ini tersedia secara khusus untuk menunjang kebutuhan referensi akademik dan literasi di lingkungan sekolah.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm sticky-panel">
                <div class="card-body p-4 bg-white border border-light">
                    <label class="text-muted fw-bold small mb-4 text-uppercase d-block tracking-wider">Panel Peminjaman</label>
                    
                    @php
                        $hasUnpaidFine = App\Models\Peminjaman::where('iduser', auth()->id())
                                        ->where('denda', '>', 0)
                                        ->where('status_bayar', 'belum')
                                        ->exists();

                        $hasPendingReturn = App\Models\Peminjaman::where('iduser', auth()->id())
                                        ->where('status', 'Dipinjam')
                                        ->exists();
                    @endphp

                    @if($hasUnpaidFine)
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                                <div>
                                    <p class="fw-bold mb-0 small">Akses Terkunci</p>
                                    <p class="mb-0 small opacity-75">Selesaikan denda Anda terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>
                        <button disabled class="btn btn-secondary w-100 py-3 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-lock-fill"></i> BLOKIR
                        </button>

                    @elseif($buku->stok_tersedia <= 0)
                        <div class="text-center py-4 bg-light rounded-3 mb-4">
                            <i class="bi bi-archive-fill fs-1 text-muted opacity-50"></i>
                            <p class="mt-2 fw-bold text-muted mb-0">Buku Belum Tersedia</p>
                        </div>
                        <button disabled class="btn btn-dark w-100 py-3 fw-bold rounded-3">STOK HABIS</button>

                    @else
                        @if($hasPendingReturn)
                            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 py-2">
                                <p class="small mb-0">
                                    <i class="bi bi-info-circle-fill"></i> Anda memiliki pinjaman aktif.
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('peminjaman.store') }}" method="POST" onsubmit="confirmLoan(event)">
                            @csrf
                            <input type="hidden" name="idbuku" value="{{ $buku->idbuku }}">
                            <input type="hidden" name="tanggal_kembali" id="tanggal_kembali_hidden">

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Jumlah Pinjam</label>
                                <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                    <button class="btn btn-white border-0 px-3" type="button" onclick="changeValue(-1)"><i class="bi bi-dash-lg"></i></button>
                                    <input type="number" name="jumlah" id="qty" value="1" min="1" 
                                           max="{{ $buku->stok_tersedia }}" 
                                           oninput="validateQty(this)"
                                           class="form-control border-0 text-center fw-bold shadow-none">
                                    <button class="btn btn-white border-0 px-3" type="button" onclick="changeValue(1)"><i class="bi bi-plus-lg"></i></button>
                                </div>
                                <div id="qty-error" class="text-danger small mt-1 d-none">Stok tidak mencukupi!</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Durasi Pinjam</label>
                                <select id="days" class="form-select form-select-lg fw-bold border-0 bg-light shadow-none" onchange="calculateDueDate()">
                                    <option value="3">3 Hari</option>
                                    <option value="7" selected>7 Hari</option>
                                    <option value="14">14 Hari</option>
                                </select>
                                <div id="date-preview" class="mt-3 p-3 bg-primary-subtle rounded-3 border border-primary-subtle text-center">
                                    <small class="text-muted d-block mb-1">Tanggal Kembali:</small>
                                    <span id="target-date" class="fw-bold text-primary"></span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow rounded-3">
                                <i class="bi bi-journal-check me-2"></i> AJUKAN SEKARANG
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const GLOBAL_MAX_DAYS = 7;

    // Gabungkan Logika Perubahan Nilai Qty
    function changeValue(delta) {
        const input = document.getElementById('qty');
        const max = parseInt(input.getAttribute('max')) || 0;
        let current = parseInt(input.value) || 1;
        let newValue = current + delta;

        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
            document.getElementById('qty-error').classList.add('d-none');
        }
    }

    function validateQty(input) {
        const max = parseInt(input.getAttribute('max')) || 0;
        const errorMsg = document.getElementById('qty-error');
        let value = parseInt(input.value);

        if (value > max) {
            input.value = max;
            errorMsg.classList.remove('d-none');
            setTimeout(() => errorMsg.classList.add('d-none'), 3000);
        } else if (isNaN(value) || value < 1) {
            input.value = 1;
        }
    }

    // Logika Perhitungan Tanggal
    function calculateDueDate() {
        const daysInput = document.getElementById('days');
        const targetText = document.getElementById('target-date');
        const hiddenInput = document.getElementById('tanggal_kembali_hidden');
        
        let days = parseInt(daysInput.value);
        if (isNaN(days)) return;

        const date = new Date();
        date.setDate(date.getDate() + days);

        targetText.innerText = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        hiddenInput.value = `${y}-${m}-${d}`;
    }

    // SweetAlert Confirmation
    function confirmLoan(event) {
        event.preventDefault();
        const form = event.target;
        const qty = document.getElementById('qty').value;
        const days = document.getElementById('days').value;
        const targetDate = document.getElementById('target-date').innerText;
        const bookTitle = "{{ $buku->judul }}";

        Swal.fire({
            title: 'Konfirmasi Pinjam',
            html: `Yakin ingin meminjam <b>${qty} buku</b> "${bookTitle}" untuk <b>${days} hari</b>?<br><br>Wajib kembali: <b>${targetDate}</b>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Ya, Ajukan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    }

    // Init on Load
    document.addEventListener('DOMContentLoaded', calculateDueDate);
</script>