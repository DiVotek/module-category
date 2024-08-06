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
        Schema::create('category_products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('product_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('sorting')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_products');
    }
};
