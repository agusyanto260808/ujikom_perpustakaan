@forelse($peminjaman as $pinjam)
<tr>
    <td>{{ $pinjam->buku->judul }}</td>
    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d-m-Y') }}</td>
    <td>
        @if($pinjam->status == 'menunggu')
            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                Menunggu Konfirmasi Petugas
            </span>
        @elseif($pinjam->status == 'dipinjam') {{-- Sesuaikan dengan status di database kamu --}}
            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                Silahkan Ambil Buku di Perpus
            </span>
        @else
            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                {{ ucfirst($pinjam->status) }}
            </span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center py-4 text-gray-500 italic">Belum ada riwayat peminjaman.</td>
</tr>
@endforelse