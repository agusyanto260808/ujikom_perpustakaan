<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // Nama tabel di database Anda
    protected $table = 'buku';

    // Primary key custom Anda
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

    // Relasi ke Model Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
