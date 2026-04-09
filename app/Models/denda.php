<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';
    protected $primaryKey = 'iddenda';

    protected $fillable = [
        'idpengembalian',
        'jumlah',
        'hari_terlambat',
        'status',
        'tarif_per_hari'
    ];

    /**
     * Relasi: idpengembalian (FK di denda) -> idkembali (PK di pengembalian)
     */
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'idpengembalian', 'idkembali');
    }
}
