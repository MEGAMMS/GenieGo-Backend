<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        });
        // Add the CHECK constraint using a raw SQL statement
        DB::statement('
            ALTER TABLE sites
            ADD CONSTRAINT check_customer_or_store
            CHECK (
                (customer_id IS NOT NULL AND store_id IS NULL) OR
                (customer_id IS NULL AND store_id IS NOT NULL)
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
