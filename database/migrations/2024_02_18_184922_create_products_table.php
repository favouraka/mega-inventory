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
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('length')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('upc_code')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable()->default('NIL');
            $table->string('batch')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('price_cfa',8,2)->nullable();
            $table->decimal('price_ngn',8,2)->nullable();
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
