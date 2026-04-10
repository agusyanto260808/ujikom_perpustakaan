<x-app-layout>
    <x-slot name="header">
        {{-- Bootstrap + Icons --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-people-fill"></i> Kelola Akun Pengguna
            </h5>
            <small class="text-muted">
                Total Pengguna: <strong>{{ $users->total() }}</strong>
            </small>
        </div>
    </x-slot>

    {{-- Latar belakang light agar sama dengan dashboard --}}
    <div class="py-5 bg-light min-vh-100">
        <div class="container">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- SEARCH CARD --}}
            <div class="card shadow-sm mb-4 border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('kelola_akun.index') }}" class="row g-3">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control border-0 bg-light"
                                    placeholder="Cari nama, email, atau NISN...">
                            </div>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary w-100 fw-bold">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('kelola_akun.index') }}" class="btn btn-outline-secondary">
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
                        <h6 class="fw-bold mb-0 text-dark">DATA PENGGUNA</h6>
                        <small class="text-secondary">Manajemen akun petugas dan siswa</small>
                    </div>

                    <a href="{{ route('kelola_akun.create') }}" class="btn btn-success btn-sm px-3 fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Akun
                    </a>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-3 small text-uppercase">Nama Pengguna</th>
                                <th class="py-3 small text-uppercase">Kontak / Email</th>
                                <th class="py-3 small text-uppercase text-center">Role</th>
                                @if(request('role') != 'petugas')
                                    <th class="py-3 small text-uppercase text-center">NISN</th>
                                @endif
                                <th class="py-3 small text-uppercase text-center pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @forelse ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle me-3 text-secondary">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    </div>
                                </td>

                                <td class="text-secondary small">{{ $user->email }}</td>

                                <td class="text-center">
                                    @if($user->role == 'petugas')
                                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3">Petugas</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3">Siswa</span>
                                    @endif
                                </td>

                                @if(request('role') != 'petugas')
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-normal">
                                        {{ $user->nisn ?? '-' }}
                                    </span>
                                </td>
                                @endif

                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- EDIT --}}
                                        <a href="{{ route('kelola_akun.edit', $user->id) }}"
                                           class="btn btn-sm btn-light border text-warning shadow-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- DELETE --}}
                                        <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                class="btn btn-sm btn-light border text-danger shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $user->id }}"
                                          action="{{ route('kelola_akun.destroy', $user->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 opacity-25"></i>
                                    <p class="mt-2">Tidak ditemukan data pengguna</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($users->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>
{{-- SweetAlert2 untuk tampilan pesan yang lebih cantik --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "Apakah Anda yakin ingin menghapus " + userName + "? Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengirim form berdasarkan ID yang unik
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
<script>
    function confirmDelete(userId, userName) {
        if (confirm("Apakah Anda yakin ingin menghapus " + userName + "?")) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>