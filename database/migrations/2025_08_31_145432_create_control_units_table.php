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

        Schema::create('control_units', function (Blueprint $table) {
            $table->id(); // الرقم التعريفي
            $table->string('name');
            $table->unsignedBigInteger('issue_type_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('main_group_id')->nullable();
            $table->unsignedBigInteger('sub_group_id')->nullable();
            $table->smallInteger('has_photos')->default(0);
            $table->tinyInteger('daily_control')->default(0);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_units');
    }
};
