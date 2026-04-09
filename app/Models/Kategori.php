<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    // Tambahkan ini agar field bisa diisi
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori punya banyak buku
    public function bukus(): HasMany
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
