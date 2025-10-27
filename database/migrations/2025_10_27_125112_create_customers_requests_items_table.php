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
        Schema::create('customers_requests_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_request_id')->constrained('customers_requests')->onDelete('cascade');
            $table->integer('product_id');
            $table->integer('product_unit_id');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_requests_items');
    }
};
