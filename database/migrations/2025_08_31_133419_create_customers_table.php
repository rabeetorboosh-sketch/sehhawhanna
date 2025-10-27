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
           Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->string('phone')->nullable(); // رقم الهاتف
                $table->unsignedBigInteger('employee_id')->nullable();       // الموظف
                $table->unsignedBigInteger('branch_id');       // الفرع
                $table->unsignedBigInteger('sales_rout_id')->nullable();       // الخط
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
