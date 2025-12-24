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
        Schema::create('pur_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pur_item_id');
            $table->unsignedBigInteger('pur_purchase_id');
            $table->unsignedInteger('pur_unit_id');
            $table->unsignedInteger('pur_purchase_count');
            $table->tinyInteger('is_confirmed')->default(0);
            $table->tinyInteger('is_intake')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pur_purchase_items');
    }
};
