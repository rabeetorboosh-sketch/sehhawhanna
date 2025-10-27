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
        Schema::create('task_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_assignment_id')->constrained('task_assignments')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedBigInteger('task_occurrence_id')->nullable();
            $table->dateTime('received_at');
            $table->integer('completion_percentage')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->text('solution_method')->nullable();
            $table->boolean('forwarded_to_management')->default(0);
            $table->text('forward_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('location')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_receipts');
    }
};
