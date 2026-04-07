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
            <table class="table table-borderless small">
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
                <tr>
                    <td class="text-muted ps-0">Status Stok</td>
                    <td class="fw-bold text-end">
                        @if($item->stok > 0)
                            <span class="badge bg-success-soft text-success border border-success px-3">Tersedia: {{ $item->stok }}</span>
                        @else
                            <span class="badge bg-danger text-white px-3">Habis</span>
                        @endif
                    </td>
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
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body p-4 bg-light rounded-4">
                    <label class="text-muted fw-bold small mb-3 text-uppercase d-block">Panel Peminjaman</label>
                    
                    @php
                        // Logika Keamanan: Cek apakah user masih meminjam buku lain
                        $hasPendingReturn = App\Models\Peminjaman::where('iduser', auth()->id())
                                            ->where('status', 'dipinjam')
                                            ->exists();
                        $maxDays = 7;
                    @endphp

                    @if($hasPendingReturn)
                        <div class="alert alert-danger border-0 shadow-sm rounded-3">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                                <div>
                                    <p class="fw-bold small mb-1">Peminjaman Ditolak</p>
                                    <p class="x-small mb-0 opacity-75" style="font-size: 0.75rem;">Anda masih memiliki buku yang belum dikembalikan.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($item->stok <= 0)
                        <div class="text-center py-3">
                            <button disabled class="btn btn-secondary w-100 py-3 fw-bold rounded-3">STOK HABIS</button>
                            <p class="text-muted small mt-2">Cek kembali dalam beberapa hari.</p>
                        </div>
                    @else
                     <form action="{{ route('peminjaman.store') }}" method="POST" onsubmit="return confirmLoan()">
    @csrf
    <input type="hidden" name="idbuku" value="{{ $item->idbuku }}">
    
    {{-- Input Hidden untuk Controller --}}
    <input type="hidden" name="tanggal_kembali" id="tanggal_kembali_hidden">

    {{-- Input Jumlah Buku --}}
    <div class="mb-4">
        <label class="form-label fw-bold small text-secondary">Jumlah Pinjam:</label>
        <div class="input-group">
            <button class="btn btn-outline-secondary" type="button" onclick="changeValue(-1)">-</button>
            <input type="number" name="jumlah" id="qty" value="1" min="1" max="{{ $item->stok }}" 
                   class="form-control text-center fw-bold">
            <button class="btn btn-outline-secondary" type="button" onclick="changeValue(1)">+</button>
        </div>
    </div>

    {{-- Input Durasi --}}
    <div class="mb-4">
        <label class="form-label small fw-bold text-muted">Durasi Pinjam (Hari)</label>
        <div class="input-group mb-2">
            <input type="number" id="days" value="7" min="1" 
                   class="form-control fw-bold border-primary shadow-none" oninput="calculateDueDate()">
            <button class="btn btn-primary fw-bold px-3" type="button" onclick="setDayLimit({{ $maxDays }})">MAX</button>
        </div>
        
        {{-- Tampilan Estimasi untuk User --}}
        <div id="date-preview" class="p-3 bg-white rounded border text-center shadow-sm">
            <span class="text-muted small text-uppercase fw-bold">Tanggal Kembali:</span><br>
            <span id="target-date" class="h5 fw-bold text-primary"></span>
            <hr class="my-2">
            <p class="mb-0 text-danger" style="font-size: 0.75rem;">
                <i class="bi bi-info-circle"></i> Melewati tanggal ini akan dianggap jatuh tempo dan dikenakan denda.
            </p>
        </div>
    </div>

    <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm rounded-3">
        <i class="bi bi-journal-plus me-1"></i> AJUKAN PINJAMAN
    </button>
</form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function setDayLimit(max) {
    document.getElementById('days').value = max;
    calculateDueDate(); // Panggil fungsi hitung tanggal
}
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
<script>
    const GLOBAL_MAX_DAYS = 7;

    function setDayLimit(max) {
        document.getElementById('days').value = max;
        calculateDueDate();
    }

    function calculateDueDate() {
    const daysInput = document.getElementById('days');
    const targetText = document.getElementById('target-date');
    const hiddenInput = document.getElementById('tanggal_kembali_hidden');
    
    let days = parseInt(daysInput.value);

    // Jika input kosong atau bukan angka, jangan proses
    if (isNaN(days) || days < 0) {
        targetText.innerText = "-";
        hiddenInput.value = "";
        return;
    }

    const date = new Date();
    date.setDate(date.getDate() + days);

    // Tampilan User (format Indonesia)
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    targetText.innerText = date.toLocaleDateString('id-ID', options);

    // Format untuk Controller (YYYY-MM-DD)
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    // Ini yang akan dikirim ke database
    hiddenInput.value = `${year}-${month}-${day}`;
}

    function confirmLoan() {
        const days = parseInt(document.getElementById('days').value);
        if (days > GLOBAL_MAX_DAYS) {
            return confirm(`Durasi ${days} hari melebihi batas standar. Lanjutkan?`);
        }
        return confirm('Apakah data peminjaman sudah benar?');
    }

   document.addEventListener('DOMContentLoaded', function() {
    const daysInput = document.getElementById('days');
    
    // Jalankan hitung tanggal saat pertama kali halaman dibuka
    calculateDueDate();

    // Jalankan setiap kali ada perubahan angka di input durasi
    daysInput.addEventListener('input', calculateDueDate);
    daysInput.addEventListener('change', calculateDueDate);
});
</script>