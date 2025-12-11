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
        Schema::create('housing_assignment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housing_assignment_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('housing_unit_room_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_assignment_items');
    }
};
