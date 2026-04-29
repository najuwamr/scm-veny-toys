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
        Schema::create('prakiraan_produksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Relasi ke tabel produk
            $table->foreignUuid('produk_id')->constrained()->onDelete('cascade');

            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->integer('qty_prediksi');
            $table->string('metode', 50); // moving_average, linear_regression, dll

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prakiraan_produksis');
    }
};
