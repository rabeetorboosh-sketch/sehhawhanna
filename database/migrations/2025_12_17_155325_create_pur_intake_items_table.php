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
        Schema::create('pur_intake_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pur_item_id');
            $table->unsignedInteger('pur_unit_id');
            $table->unsignedInteger('pur_intake_id');
            $table->unsignedInteger('pur_intake_count');
            $table->tinyInteger('is_confirmed')->default(0);
            $table->tinyInteger('is_load')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pur_intake_items');
    }
};
