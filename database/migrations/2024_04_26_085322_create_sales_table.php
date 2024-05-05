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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('order_id')->constrained('orders');
            $table->unsignedInteger('quantity');
            $table->unsignedDecimal('sale_price', 10, 2);
            $table->unsignedDecimal('stock_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
