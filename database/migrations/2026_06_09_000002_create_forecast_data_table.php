<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forecast_product_id');
            $table->date('period');
            $table->integer('actual_qty');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('forecast_product_id')->references('id')->on('forecast_products')->cascadeOnDelete();
            $table->unique(['forecast_product_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_data');
    }
};
