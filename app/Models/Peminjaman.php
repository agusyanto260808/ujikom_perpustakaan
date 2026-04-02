<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    // Sesuaikan dengan HeidiSQL kamu yang menuliskan 'idpinjam'
    protected $primaryKey = 'idpinjam'; // Cek apakah di DB namanya idpinjam atau idpeminjaman
    public $incrementing = true;

    protected $fillable = [
        'iduser',        // Penting: database kamu pakai iduser, bukan nama_peminjam
        'idbuku',
        'tgl_pinjam',
        'tanggal_jatuh_tempo', // Sesuaikan dengan nama di HeidiSQL
        'status',
        'jumlah',
        'denda'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku', 'idbuku');
    }

    // Tambahkan relasi User agar nama peminjam bisa muncul di tabel admin
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
