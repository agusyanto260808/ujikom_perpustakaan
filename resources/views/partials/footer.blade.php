<footer class="mt-auto bg-white border-top py-4">
    <div class="container">
        <div class="row align-items-center g-3">
            {{-- Bagian Kiri: Copyright --}}
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted small">
                    &copy; {{ date('Y') }} <span class="fw-bold text-dark">SMK NEGERI 3 BANJAR</span>. 
                    Seluruh Hak Cipta Dilindungi.
                </p>
            </div>

            {{-- Bagian Kanan: Status & Links --}}
            
           <div class="col-md-6 text-center text-md-end">
                <p class="text-uppercase ls-wide text-muted" style="font-size: 0.65rem; letter-spacing: 1px;">
                    Dikembangkan untuk Manajemen Perpustakaan Digital
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
}

footer {
    background-color: #ffffff;
}

.hover-dark:hover {
    color: #212529 !important;
    transition: color 0.3s ease;
}

/* Tipografi Halus */
.ls-wide {
    letter-spacing: 1.5px;
}
</style>