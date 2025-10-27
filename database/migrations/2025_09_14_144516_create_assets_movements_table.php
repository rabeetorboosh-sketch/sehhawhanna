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
        Schema::create('assets_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // المستخدم الذي قام بالحركة
            $table->unsignedBigInteger('asset_number'); // رقم الأصل
            $table->unsignedBigInteger('from_item')->nullable(); // من أيتم (رقم)
            $table->unsignedBigInteger('from_item_type')->nullable(); // نوع من أيتم
            $table->unsignedBigInteger('to_item'); // إلى أيتم (رقم)
            $table->unsignedBigInteger('to_item_type'); // نوع إلى أيتم
            $table->dateTime('movement_datetime'); // تاريخ ووقت النقل
            $table->text('reason')->nullable(); // سبب النقل
            $table->string('asset_status'); // حالة الأصل بعد النقل
            $table->string('movement_destination')->nullable(); // جهة النقل أو القسم المستقبل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_movements');
    }
};
