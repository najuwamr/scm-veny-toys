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
        Schema::table('invoices', function (Blueprint $table) {
            // Tambah kolom pembayaran dinamis
            $table->integer('nominal_terbayar')->default(0)->after('jumlah_tagihan');
            $table->integer('sisa_tagihan')->default(0)->after('nominal_terbayar');
            $table->string('bukti_pembayaran')->nullable()->after('metode_bayar');
            $table->timestamp('verified_at')->nullable()->after('bukti_pembayaran');
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('verified_at');
            
            // Ubah metode_bayar dari nullable menjadi required dengan default
            $table->string('metode_bayar', 50)->default('transfer')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['nominal_terbayar', 'sisa_tagihan', 'bukti_pembayaran', 'verified_at', 'verified_by']);
        });
    }
};
