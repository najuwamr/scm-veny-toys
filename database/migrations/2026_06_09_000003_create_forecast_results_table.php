<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forecast_product_id');
            $table->date('forecast_period');
            $table->integer('forecast_qty');
            $table->decimal('mad', 10, 4);
            $table->decimal('mape', 10, 4);
            $table->enum('method_used', ['sma', 'wma']);
            $table->integer('window_size')->default(3);
            $table->timestamps();

            $table->foreign('forecast_product_id')->references('id')->on('forecast_products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_results');
    }
};
