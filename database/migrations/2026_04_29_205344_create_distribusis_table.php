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
        Schema::create('distribusis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pesanan_id')->constrained()->onDelete('cascade');
            $table->foreignId('metode_pengiriman_id')->constrained('metode_pengirimans');
            $table->enum('status', ['dijadwalkan', 'dikirim', 'dalam_perjalanan', 'diterima'])->default('dijadwalkan');
            $table->timestamp('tgl_dijadwalkan')->nullable();
            $table->timestamp('tgl_diterima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};
