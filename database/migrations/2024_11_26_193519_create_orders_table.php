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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Foreign key to 'customers' table
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); // Foreign key to 'stores' table
            $table->decimal('total_price', 10, 2); // Total price of the Order
            $table->string('status'); // Order's status; e.g 'pending','delivered'..etc 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};