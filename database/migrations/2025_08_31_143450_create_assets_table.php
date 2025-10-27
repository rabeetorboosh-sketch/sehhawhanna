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
        Schema::create('assets', function (Blueprint $table) {
            $table->id(); // رقم تعريفي تلقائي
            $table->unsignedBigInteger('item_id');
            $table->date('usage_date')->nullable();
            $table->integer('lifetime')->nullable();
            $table->string('id_number')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
