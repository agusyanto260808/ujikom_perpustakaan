<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a; /* Warna gelap sesuai gambar Anda */
        }
        .card {
            border-radius: 1rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" width="60">
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Login</h3>
                            <p class="text-muted small">Silakan masuk ke akun perpustakaan Anda</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success small mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                       <form method="POST" action="{{ route('login') }}">
                             @csrf
    
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <input id="email" type="email" name="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" required autofocus placeholder="Masukan Email anda">
                                @error('email')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-bold text-secondary">Password</label>
                                <input id="password" type="password" name="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    required placeholder="Masukan Password anda">
                                @error('password')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                    <label class="form-check-label small text-muted" for="remember_me">
                                        Ingat Saya
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                    Masuk
                                </button>
                            </div>

                            {{-- <div class="text-center mt-4">
                                <p class="small text-muted mb-0">Belum punya akun? 
                                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar</a>
                                </p>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>