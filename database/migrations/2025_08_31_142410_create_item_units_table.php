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
        Schema::create('item_units', function (Blueprint $table) {
            $table->id(); // الرقم
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();   // رقم الصنف
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();   // رقم الوحدة
            $table->boolean('is_main')->default(false); // رئيسية (0 = لا، 1 = نعم)
            $table->decimal('package');
            $table->unsignedBigInteger('branch_id'); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_units');
    }
};
