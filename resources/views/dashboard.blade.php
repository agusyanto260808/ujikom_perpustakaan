<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Baris Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold uppercase">Total Buku</p>
                    <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalBuku }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border-l-4 border-emerald-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold uppercase">Total Anggota</p>
                    <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalUser }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border-l-4 border-amber-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold uppercase">Total Transaksi</p>
                    <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalPinjam }}</h3>
                </div>
            </div>

            {{-- Bagian Diagram --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-8">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6 uppercase tracking-wider">
                    Statistik Peminjaman Bulanan
                </h3>
                
                <div class="relative" style="height: 400px;">
                    <canvas id="peminjamanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Jenis diagram batang
            data: {
                labels: {!! json_encode($labels) !!}, // Nama-nama bulan
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($values) !!}, // Angka total
                    backgroundColor: 'rgba(59, 130, 246, 0.5)', // Warna biru Tailwind
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-app-layout>