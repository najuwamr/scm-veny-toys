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
        Schema::table('pengiriman_pengadaans', function (Blueprint $table) {
            $table->string('ekspedisi', 100)->nullable()->after('status');
            $table->string('no_resi', 100)->nullable()->after('ekspedisi');
            $table->timestamp('estimasi_tiba')->nullable()->after('tgl_kirim');
            $table->text('catatan')->nullable()->after('tgl_terima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman_pengadaans', function (Blueprint $table) {
            $table->dropColumn(['ekspedisi', 'no_resi', 'estimasi_tiba', 'catatan']);
        });
    }
};
