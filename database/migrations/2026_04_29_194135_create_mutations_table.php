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
        Schema::create('mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Polymorphic:
            // mutatable_id akan menyimpan UUID dari bahan_baku atau produk
            // mutatable_type akan menyimpan string nama modelnya (misal: "App\Models\BahanBaku")
            $table->uuidMorphs('mutatable');
            
            $table->enum('jenis_mutasi', ['masuk', 'keluar']);
            $table->decimal('jumlah', 10, 2); // Pakai decimal agar konsisten dengan stok bahan baku
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
