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
        Schema::create('pur_factory_intake', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pur_load_id')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->date('factory_intake_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pur_factory_intake');
    }
};
