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
        Schema::create('problem_assignments', function (Blueprint $table) {
            $table->id(); // رقم الإسناد
            $table->unsignedBigInteger('problem_id')->nullable(); // رقم المشكلة
            $table->unsignedBigInteger('assigned_by')->nullable(); // الشخص الذي أسندها
            $table->unsignedBigInteger('assigned_to')->nullable(); // الشخص الذي أُسندت إليه
            $table->string('assignment_type')->nullable(); // نوع الإسناد هل رقابة يومية او بلاغ
            $table->text('note')->nullable(); // ملاحظة
            $table->string('status')->nullable(); // حالة الإسناد
            $table->unsignedBigInteger('branch_id')->nullable(); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_assignments');
    }
};
