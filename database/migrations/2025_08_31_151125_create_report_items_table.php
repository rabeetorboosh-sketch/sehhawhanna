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
        Schema::create('report_items', function (Blueprint $table) {
            $table->id(); // الرقم التعريفي
            $table->unsignedBigInteger('item_no')->nullable(); // رقم البند
            $table->string('item_type'); // نوع البند
            $table->unsignedBigInteger('report_id'); // رقم البلاغ
            $table->unsignedBigInteger('control_unit_id')->nullable(); // رقم المشكلة
            $table->string('user_control_unit')->nullable(); // رقم المشكلة
            $table->unsignedBigInteger('causer_id')->nullable(); // رقم المتسبب
            $table->text('issue_description')->nullable(); // وصف المشكلة
            $table->string('response_status')->nullable(); // حالة الاستجابة
            $table->unsignedBigInteger('branch_id')->nullable(); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};
