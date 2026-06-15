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
       Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_bahan', 50)->unique();
            $table->string('nama_bahan', 150);
            $table->enum('kategori', ['kain', 'isi boneka', 'aksesoris'])->nullable();
            $table->enum('satuan', ['rol', 'kg', 'pcs']);
            $table->decimal('stok_saat_ini', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
