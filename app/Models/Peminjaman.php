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
    // Pastikan bagian ini di model Peminjaman kamu tetap seperti ini:
    public function denda_relation()
    {
        return $this->hasOneThrough(
            Denda::class,
            Pengembalian::class,
            'idpinjam',       // Foreign key di tabel pengembalian
            'idpengembalian', // Foreign key di tabel denda
            'idpinjam',       // Local key di tabel peminjaman
            'idkembali'       // Local key di tabel pengembalian
        );
    }
    public function hitungDendaOtomatis()
    {
        // Pastikan kita hanya mengambil tanggalnya saja tanpa jam
        $tglJatuhTempo = \Carbon\Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay();
        $tglKembali = \Carbon\Carbon::today(); // Jam 00:00:00 hari ini

        if ($tglKembali->gt($tglJatuhTempo)) {
            // Gunakan parameter true pada diffInDays untuk mendapatkan nilai absolut
            $hariTerlambat = $tglKembali->diffInDays($tglJatuhTempo, true);

            // Atau bungkus dengan abs()
            return abs($hariTerlambat) * 2000;
        }

        return 0;
    }
}
