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
        Schema::create('pengiriman_pengadaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('permintaan_pengadaan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['disiapkan', 'dikirim', 'dalam_perjalanan', 'diterima'])->default('disiapkan');
            $table->timestamp('tgl_kirim')->nullable();
            $table->timestamp('tgl_terima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_pengadaans');
    }
};
