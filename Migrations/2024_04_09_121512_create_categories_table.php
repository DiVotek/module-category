<?php

use App\Models\StaticPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Category\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Category::getDb(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->integer('sorting')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('views')->default(0);
            $table->json('template')->default(json_encode([]));
            Category::timestampFields($table);
        });
        StaticPage::createSystemPage('Catalog', 'catalog');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Category::getDb());
    }
};
