<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // site's name
            $table->text('address'); // site's address
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to 'customers' table
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to 'stores' table
            $table->timestamps();

            // Add a check constraint to ensure exactly one of customer_id or store_id is non-null
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
