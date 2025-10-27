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
        Schema::create('store_transactions_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_transactions_id')->constrained('store_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('store_transactions_items');
    }
};
