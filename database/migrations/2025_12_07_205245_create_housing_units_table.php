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
        Schema::create('housing_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code');
            $table->string('name')->nullable();
            $table->string('unit_type')->nullable();
            $table->integer('total_rooms')->default(0);
            $table->integer('total_kitchens')->default(0);
            $table->integer('total_bathrooms')->default(0);
            $table->string('status')->default('available');
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_units');
    }
};
