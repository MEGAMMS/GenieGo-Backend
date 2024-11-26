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
        Schema::create('store_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); // Foreign key to 'stores' table
            $table->string('language'); // Language code, e.g., 'en', 'ar'
            $table->string('name'); // Translated name
            $table->timestamps();

            // Unique constraint for store_id and language
            $table->unique(['store_id', 'language'], 'store_language_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_translations');
    }
};
