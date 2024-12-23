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
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to 'products' table
            $table->string('language'); // Language code, e.g., 'en', 'ar'
            $table->string('name'); // Translated name
            $table->longText('description')->nullable(); // Translated description
            $table->timestamps();

            // Unique constraint for product_id and language
            $table->unique(['product_id', 'language'], 'product_language_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
