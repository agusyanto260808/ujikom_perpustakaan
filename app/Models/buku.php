<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'idbuku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'gambar',
        'iduser',
        'kategori_id'
    ];

    // 1. Relasi ke Peminjaman (Penting untuk hitung stok tersedia)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'idbuku', 'idbuku');
    }

    // 2. Accessor Stok Tersedia
    // Ini akan dipanggil di Blade dengan $item->stok_tersedia
    public function getStokTersediaAttribute()
    {
        // Hitung total buku yang sedang dipinjam (bukan barisnya)
        // Filter status: buku dianggap "keluar" jika statusnya bukan 'Kembali' atau 'Ditolak'
        $sedangDipinjam = $this->peminjamans()
            ->whereNotIn('status', ['Kembali', 'Ditolak'])
            ->sum('jumlah');

        $sisa = $this->stok - $sedangDipinjam;

        return $sisa < 0 ? 0 : $sisa;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
