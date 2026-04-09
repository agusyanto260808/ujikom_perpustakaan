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

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    // 1. Relasi ke Buku (Cukup SATU saja)
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku', 'idbuku');
    }

    // 2. Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    // 3. Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'idpinjam', 'idpinjam');
    }

    // 4. Relasi ke Denda (melalui Pengembalian)
    public function denda_relation()
    {
        // Saya beri nama denda_relation agar tidak bentrok dengan KOLOM 'denda' di tabel peminjaman
        return $this->hasOneThrough(
            Denda::class,
            Pengembalian::class,
            'idpinjam',       // FK di pengembalian
            'idpengembalian', // FK di denda
            'idpinjam',       // Local key di peminjaman
            'idkembali'       // Local key di pengembalian
        );
    }
}
