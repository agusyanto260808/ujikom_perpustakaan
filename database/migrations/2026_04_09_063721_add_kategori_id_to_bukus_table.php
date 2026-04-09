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
        // Ganti 'bukus' menjadi 'buku'
        Schema::table('buku', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id')->nullable()->after('idbuku');

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Ganti 'bukus' menjadi 'buku'
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
