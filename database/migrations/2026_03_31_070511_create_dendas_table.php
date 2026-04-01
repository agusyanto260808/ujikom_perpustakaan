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
        Schema::create('denda', function (Blueprint $table) {
            $table->id('iddenda');
            $table->foreignId('idpengembalian')->constrained('pengembalian', 'idkembali')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('hari_terlambat');
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->integer('tarif_per_hari')->default(2000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
