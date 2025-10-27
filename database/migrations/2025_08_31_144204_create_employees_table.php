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
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // رقم تعريفي تلقائي
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nationality')->nullable(); // الجنسية
            $table->integer('age')->nullable(); // العمر
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('id_number')->nullable(); // رقم الهوية
            $table->string('email')->nullable(); // رقم الهوية
            $table->date('id_expiry_date')->nullable(); // تاريخ انتهاء الهوية
            $table->unsignedBigInteger('branch_id')->nullable(); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
