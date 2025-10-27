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
        Schema::create('task_assignment_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_assignment_id');
            $table->tinyInteger('day_of_week')->nullable();  // 0=الأحد .. 6=السبت
            $table->tinyInteger('day_of_month')->nullable(); // 1..31
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignment_days');
    }
};
