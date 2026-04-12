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

        .search-input {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .search-input:focus {
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

        .avatar-circle {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #64748b;
            border-radius: 12px;
            font-size: 1.2rem;
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

        .badge-modern {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
        }
    </style>

    <div class="header-gradient text-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1 h2">Kelola Akun</h1>
                    <p class="opacity-75 mb-0">Total terdapat {{ $users->total() }} pengguna terdaftar</p>
                </div>
                <a href="{{ route('kelola_akun.create') }}" class="btn btn-light btn-modern text-primary shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Akun Baru
                </a>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        {{-- SEARCH SECTION --}}
        <div class="modern-card p-4 mb-4">
            <form method="GET" action="{{ route('kelola_akun.index') }}" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control search-input border-start-0 ps-0"
                               placeholder="Cari berdasarkan nama, email, atau NISN...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-modern w-100 shadow-sm" style="background: #4158D0; border: none;">
                        Cari Data
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="modern-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Nama Pengguna</th>
                            <th>Email & Kontak</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">UID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $user->email }}</div>
                                <small class="text-secondary">Akun Aktif</small>
                            </td>
                            <td class="text-center">
                                @if($user->role == 'petugas')
                                    <span class="badge badge-modern bg-primary-subtle text-primary">PETUGAS</span>
                                @else
                                    <span class="badge badge-modern bg-warning-subtle text-warning-emphasis">SISWA</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <code class="bg-light px-2 py-1 rounded text-dark">{{ $user->nisn ?? '-' }}</code>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('kelola_akun.edit', $user->id) }}"
                                       class="action-btn bg-info-subtle text-info border-0" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
        onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
        class="action-btn bg-danger-subtle text-danger border-0" 
        title="Hapus Data">
    <i class="bi bi-trash3"></i>
</button>

<form id="delete-form-{{ $user->id }}" action="{{ route('kelola_akun.destroy', $user->id) }}" method="POST" class="d-none">
    @csrf 
    @method('DELETE')
</form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/searching.svg" alt="Empty" style="width: 150px;" class="mb-3">
                                <p class="text-muted">Ops! Tidak ditemukan data pengguna yang Anda cari.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-4 py-4 border-top">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
{{-- buat hapus --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus Akun?',
            text: "Apakah Anda yakin ingin menghapus " + userName + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jalankan submit form
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>