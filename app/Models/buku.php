<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    // TAMBAHKAN BARIS INI
    protected $primaryKey = 'idbuku';

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
