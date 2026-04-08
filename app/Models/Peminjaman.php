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
        'tanggalkembali', // TAMBAHKAN INI
        'status',
        'jumlah',
        'denda'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku', 'idbuku');
    }
    // app/Models/Peminjaman.php
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    // TAMBAHKAN KODE INI
    // App\Models\Peminjaman.php
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'idpinjam', 'idpinjam');
    }
}
