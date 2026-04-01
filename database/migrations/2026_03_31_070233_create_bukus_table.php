<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id('idbuku');
            $table->string('judul', 225);
            $table->string('penulis', 225)->nullable();
            $table->string('penerbit', 225)->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('stok')->nullable();
            $table->string('gambar', 225)->nullable();
            $table->foreignId('iduser')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
