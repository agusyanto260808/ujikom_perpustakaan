<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use App\Models\Buku;    


class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian'; // Pastikan nama tabel di database sesuai
    protected $primaryKey = 'idkembali';
    public $incrementing = true;

    protected $fillable = [
        'idpinjam',
        'tanggalkembali',
    ];

    /**
     * Relasi ke model Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'idpinjam', 'idpinjam');
    }

    /**
     * Relasi shortcut ke Buku melalui Peminjaman
     */
    public function buku()
    {
        return $this->hasOneThrough(
            Buku::class,
            Peminjaman::class,
            'idpinjam', // Foreign key di tabel peminjaman
            'idbuku',   // Foreign key di tabel buku
            'idpinjam', // Local key di tabel pengembalian
            'idbuku'    // Local key di tabel peminjaman
        );
    }
    public function denda()
    {
        // Sesuaikan foreign key dengan kolom 'idpengembalian' di screenshot
        return $this->hasOne(Denda::class, 'idpengembalian', 'idkembali');
    }
}
