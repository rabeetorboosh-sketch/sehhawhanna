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
        Schema::create('stores', function (Blueprint $table) {
            $table->id(); // الرقم التعريفي
            $table->string('name'); // اسم المستودع
            $table->string('type')->nullable(); // نوع المستودع
            $table->string('location')->nullable(); // الموقع
            $table->unsignedBigInteger('employee_id')->nullable(); // الموظف
            $table->unsignedBigInteger('branch_id'); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
