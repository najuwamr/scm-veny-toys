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
            $table->integer('nominal_terbayar')->default(0);
            $table->integer('sisa_tagihan');
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->date('tgl_bayar')->nullable();
            $table->string('metode_bayar', 50)->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->onDelete('set null');
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
