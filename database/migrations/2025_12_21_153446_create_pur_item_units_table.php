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
        Schema::create('pur_item_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pur_item_id')->constrained('pur_items'); // رقم الصنف
            $table->string('pur_unit_id'); // اسم الوحدة (مثل: كرتون، حبة، كيلو)
            $table->decimal('quantity', 10, 3); // الكمية
            $table->string('symbol')->nullable(); // الرمز (اختياري)
            $table->boolean('is_default')->default(false); // هل الوحدة افتراضية للصنف؟
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pur_item_units');
    }
};
