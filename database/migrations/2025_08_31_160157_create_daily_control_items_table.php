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
        Schema::create('daily_control_items', function (Blueprint $table) {
            $table->id(); // رقم البند
            $table->unsignedBigInteger('dailyControl_id') ;// وحدة الرقابة
            $table->unsignedBigInteger('control_unit_id') ;// وحدة الرقابة
            $table->unsignedBigInteger('item_id')->nullable(); // البند
            $table->unsignedBigInteger('causer_id')->nullable(); // المتسبب
            $table->text('description')->nullable(); // وصف المشكلة
            $table->unsignedBigInteger('branch_id');
            $table->tinyInteger('is_correct')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_control_items');
    }
};
