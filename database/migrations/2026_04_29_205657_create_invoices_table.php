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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pesanan_id')->constrained()->onDelete('cascade');
            $table->string('no_invoice', 50)->unique();
            $table->integer('jumlah_tagihan');
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->date('tgl_bayar')->nullable();
            $table->enum('metode_bayar', ['transfer bank', 'e-wallet', 'cod'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
