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
        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // رقم البلاغ
            $table->unsignedBigInteger('user_id')->nullable(); // المستخدم
            $table->unsignedBigInteger('issue_type_id')->nullable(); // نوع البلاغ
            $table->string('status')->default(0); // حالة البلاغ
            $table->unsignedBigInteger('department_id'); // القسم
            $table->unsignedBigInteger('branch_id'); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
