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
        Schema::create('bahan_baku_supplier', function (Blueprint $table) {
            $table->id(); // Pivot table biasanya pakai auto-increment biasa tidak masalah
            $table->foreignUuid('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('bahan_baku_id')->constrained()->onDelete('cascade');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku_supplier');
    }
};
