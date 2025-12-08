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
        Schema::create('housing_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housing_unit_id');
            $table->string('room_name');
            $table->integer('bed_count')->default(1);
            $table->string('room_type')->nullable();
            $table->boolean('has_bathroom')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_rooms');
    }
};
