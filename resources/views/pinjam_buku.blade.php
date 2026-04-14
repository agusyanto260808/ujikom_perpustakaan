<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('katalog_buku.index') }}" class="text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $buku->judul }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('images/default.png') }}" 
                     class="card-img-top rounded shadow" alt="{{ $buku->judul }}">
            </div>
        </div>

        <div class="col-md-5">
            <p class="text-primary fw-bold text-uppercase small mb-1">{{ $buku->penulis }}</p>
            <h1 class="display-5 fw-bold mb-4">{{ $buku->judul }}</h1>

            <h5 class="fw-bold border-bottom pb-2 mb-3">Detail Buku</h5>
            <table class="table table-borderless small">
                <tr>
                    <td class="text-muted ps-0">Penerbit</td>
                    <td class="fw-bold text-end">{{ $buku->penerbit }}</td>
                </tr>
                <tr>
                    <td class="text-muted ps-0">Tahun Terbit</td>
                    <td class="fw-bold text-end">{{ $buku->tahun_terbit ?? $buku->tahun }}</td>
                </tr>
                <tr>
                    <td class="text-muted ps-0">Kategori</td>
                    <td class="fw-bold text-end text-primary">Edukasi & Referensi</td>
                </tr>
               {{-- Status Stok di Detail --}}
<tr>
    <td class="text-muted ps-0">Status Stok</td>
    <td class="fw-bold text-end">
        @if($buku->stok_tersedia > 0)
            <span class="badge bg-success-soft text-success border border-success px-3">
                Tersedia: {{ $buku->stok_tersedia }}
            </span>
        @else
            <span class="badge bg-danger text-white px-3">Habis (Sedang Dipinjam)</span>
        @endif
    </td>
</tr>
            </table>

            <div class="mt-4">
                <h5 class="fw-bold">Sinopsis / Deskripsi</h5>
                <p class="text-secondary leading-relaxed">
                    Buku <strong>{{ $buku->judul }}</strong> merupakan karya literatur berkualitas yang disusun oleh <strong>{{ $buku->penulis }}</strong>. 
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
                    @elseif($buku->stok <= 0)
                        <div class="text-center py-3">
                            <button disabled class="btn btn-secondary w-100 py-3 fw-bold rounded-3">STOK HABIS</button>
                            <p class="text-muted small mt-2">Cek kembali dalam beberapa hari.</p>
                        </div>
                    @else
                    <form action="{{ route('peminjaman.store') }}" method="POST" onsubmit="confirmLoan(event)">
    @csrf
    <input type="hidden" name="idbuku" value="{{ $buku->idbuku }}">
    
    {{-- Input Hidden untuk Controller --}}
    <input type="hidden" name="tanggal_kembali" id="tanggal_kembali_hidden">

    {{-- Input Jumlah Buku --}}
<div class="mb-4">
    <label class="form-label fw-bold small text-secondary">Jumlah Pinjam:</label>
    <div class="input-group">
        <button class="btn btn-outline-secondary" type="button" onclick="changeValue(-1)">-</button>
        {{-- Gunakan stok_tersedia sebagai batas maksimal --}}
        <input type="number" name="jumlah" id="qty" value="1" min="1" 
               max="{{ $buku->stok_tersedia }}" 
               oninput="validateQty(this)"
               class="form-control text-center fw-bold shadow-none">
        <button class="btn btn-outline-secondary" type="button" onclick="changeValue(1)">+</button>
    </div>
    <div id="qty-error" class="text-danger small mt-1 d-none">
        <i class="bi bi-exclamation-circle"></i> Stok tersedia hanya {{ $buku->stok_tersedia }}.
    </div>
</div>

    {{-- Input Durasi --}}
    {{-- Input Durasi (Dropdown) --}}
<div class="mb-4">
    <label class="form-label small fw-bold text-muted">Durasi Pinjam</label>
    <div class="mb-2">
        <select id="days" class="form-select fw-bold border-primary shadow-none" onchange="calculateDueDate()">
            <option value="3" selected>3 Hari </option>
            <option value="7" selected>7 Hari </option>
            <option value="14">14 Hari </option>
            <option value="30">30 Hari </option>
        </select>
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
    function changeValue(delta) {
        const input = document.getElementById('qty');
        const errorMsg = document.getElementById('qty-error');
        // Ambil nilai maksimal dari atribut max yang dikirim server
        const max = parseInt(input.getAttribute('max')) || 0;
        let current = parseInt(input.value) || 0;
        
        let newValue = current + delta;

        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
            errorMsg.classList.add('d-none');
        } else if (newValue > max) {
            // Jika tombol + ditekan tapi sudah mentok stok
            input.value = max;
            errorMsg.classList.remove('d-none');
        }
    }

    function validateQty(input) {
        const max = parseInt(input.getAttribute('max')) || 0;
        const errorMsg = document.getElementById('qty-error');
        let value = parseInt(input.value);

        if (isNaN(value) || value < 1) {
            input.value = 1;
            errorMsg.classList.add('d-none');
        } else if (value > max) {
            // Jika siswa mengetik angka lebih besar dari stok tersedia
            input.value = max;
            errorMsg.classList.remove('d-none');
            
            // Hilangkan pesan error setelah 3 detik
            setTimeout(() => {
                errorMsg.classList.add('d-none');
            }, 3000);
        } else {
            errorMsg.classList.add('d-none');
        }
    }

    function confirmLoan() {
        const qty = document.getElementById('qty').value;
        const bookTitle = "{{ $buku->judul }}";
        
        return confirm(`Apakah Anda yakin ingin meminjam ${qty} buku "${bookTitle}"?`);
    }
</script>
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
function confirmLoan(event) {
    // Mencegah form terkirim otomatis
    event.preventDefault();
    
    const form = event.target;
    const qty = document.getElementById('qty').value;
    const days = document.getElementById('days').value;
    const targetDate = document.getElementById('target-date').innerText;
    const bookTitle = "{{ $buku->judul }}";

    Swal.fire({
        title: 'Konfirmasi Peminjaman',
        html: `
            <div class="text-start">
                <p>Apakah Anda yakin ingin meminjam buku ini?</p>
                <table class="table table-sm borderless small">
                    <tr><td><b>Judul</b></td><td>: ${bookTitle}</td></tr>
                    <tr><td><b>Jumlah</b></td><td>: ${qty} Buku</td></tr>
                    <tr><td><b>Durasi</b></td><td>: ${days} Hari</td></tr>
                    <tr><td><b>Kembali</b></td><td>: <span class="text-primary fw-bold">${targetDate}</span></td></tr>
                </table>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754', // Warna hijau Bootstrap
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="bi bi-check-circle"></i> Ya, Pinjam!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading sebentar sebelum submit
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>