<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <button onclick="window.print()" class="mb-4 bg-blue-500 text-white px-4 py-2 rounded no-print">
                    Cetak Laporan
                </button>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->user->name }}</td>
                            <td>{{ $item->buku->judul }}</td>
                            <td>{{ $item->tanggal_pinjam }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</x-app-layout>