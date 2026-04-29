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
        Schema::create('permintaan_pengadaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Relasi ke tabel pivot bahan_baku_supplier
            $table->foreignId('bahan_baku_supplier_id')->constrained('bahan_baku_supplier')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_pengadaans');
    }
};
