<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('produk_id')->nullable();
            $table->string('name');
            $table->enum('category', ['domba', 'shopee']);
            $table->string('sku')->nullable();
            $table->string('unit_size')->nullable();
            $table->enum('forecast_method', ['sma', 'wma']);
            $table->enum('granularity', ['monthly', 'weekly']);
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_products');
    }
};
