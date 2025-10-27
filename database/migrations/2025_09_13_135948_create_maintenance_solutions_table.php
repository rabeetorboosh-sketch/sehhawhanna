<?php

// database/migrations/xxxx_xx_xx_create_maintenance_solutions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_solutions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('maintenance_request_id')
                ->constrained('maintenance_requests')
                ->onDelete('cascade');

            $table->text('issue_reason')->nullable(); // سبب المشكلة
            $table->text('solution_text')->nullable(); // حل المشكلة
            $table->float('time_spent')->nullable(); // الوقت المستغرق لحل المشكلة بالساعات
            $table->text('bad_parts')->nullable(); // القطع الغير صالحة
            $table->string('workshop_name')->nullable(); // اسم الورشة
            $table->string('maintenance_responsible')->nullable(); // اسم مسئول الصيانة
            $table->decimal('repair_cost', 10, 2)->nullable(); // تكلفة الإصلاح

            $table->boolean('temporary_solution')->default(0); // هل الحل مؤقت؟

            $table->boolean('has_warranty')->default(0); // هل يوجد ضمان على القطع؟
            $table->string('warranty_type')->nullable(); // نوع الضمانة
            $table->date('warranty_expiry')->nullable(); // صلاحية الضمان

            $table->boolean('delivered')->default(0); // هل تم تسليمها؟

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_solutions');
    }
};
