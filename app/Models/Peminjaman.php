<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan di database (misal: peminjaman)
    protected $table = 'peminjaman';

    // Sesuaikan Primary Key jika bukan 'id'
    protected $primaryKey = 'idpeminjaman';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'idbuku',
        'nama_peminjam',
        'tgl_pinjam',
        'tgl_kembali',
        'status'
    ];

    /**
     * Relasi ke model Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku', 'idbuku');
    }
}
