<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('idkembali');
            $table->date('tanggalkembali');
            $table->foreignId('idpinjam')->unique()->constrained('peminjaman', 'idpinjam')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // PERBAIKAN: Hapus akhiran 's'
        Schema::dropIfExists('pengembalian');
    }
};
