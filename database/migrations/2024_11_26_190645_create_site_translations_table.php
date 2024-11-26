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
        Schema::create('site_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade'); // Foreign key to 'sites' table
            $table->string('language'); // Language code, e.g., 'en', 'ar'
            $table->string('name'); // Translated name
            $table->timestamps();

            // Unique constraint for site_id and language
            $table->unique(['site_id', 'language'], 'site_language_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_translations');
    }
};
