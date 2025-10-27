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
        Schema::create('task_occurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // اليوم الفعلي للمهمة
            $table->boolean('is_generated')->default(false); // تقدر تستخدمها لو بتعمل generate اوتوماتيكي
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_occurrences');
    }
};
