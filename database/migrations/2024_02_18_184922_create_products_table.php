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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('weight');
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->unsignedInteger('height');
            $table->unsignedInteger('upc_code')->nullable();
            $table->string('sku_code')->nullable();
            $table->decimal('price_cfa',8,2);
            $table->decimal('price_ngn',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
