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
        Schema::create('system_movements', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');          // اسم الحقل الذي تغير
            $table->text('old_value')->nullable(); // قيمته قبل التغيير
            $table->text('new_value')->nullable(); // قيمته بعد التغيير
            $table->unsignedBigInteger('invoice_id');     // رقم الفاتورة
            $table->string('invoice_type');        // نوع الفاتورة
            $table->unsignedBigInteger('user_id'); // رقم المستخدم
            $table->timestamp('modified_at');      // تاريخ التعديل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_movements');
    }
};
