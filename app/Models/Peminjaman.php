<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'idpinjam';
    public $incrementing = true;

    protected $fillable = [
        'iduser',
        'idbuku',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'status',
        'jumlah',
        'denda'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku', 'idbuku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
