<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel (karena bukan 'bukus')
    protected $table = 'buku';

    // 2. Tentukan Primary Key (karena bukan 'id')
    protected $primaryKey = 'idbuku';

    // 3. Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'gambar',
        'iduser'
    ];
}
