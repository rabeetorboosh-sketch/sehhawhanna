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
        Schema::create('supervise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('name');
            $table->unsignedBigInteger('employee_id');
            $table->string('phone')->nullable();
            $table->text('issue')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->text('solution_method')->nullable();
            $table->text('delay_reason')->nullable();
            $table->boolean('transferred_to_management')->default(false);
            $table->text('transfer_reason')->nullable();
            $table->string('image')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervise');
    }
};
