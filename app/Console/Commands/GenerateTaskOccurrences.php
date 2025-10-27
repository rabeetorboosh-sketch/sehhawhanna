<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskAssignment;
use App\Models\TaskOccurrence;
use Carbon\Carbon;

class GenerateTaskOccurrences extends Command
{
    /**
     * اسم الكوماند
     */
    protected $signature = 'tasks:generate-occurrences';
    protected $description = 'توليد نسخ يومية للمهام بناءً على التكرار (يومي، أسبوعي، شهري)';

    public function handle()
    {
        $today = Carbon::today();
        $dayOfWeek =$today->dayOfWeekIso==6?0: $today->dayOfWeekIso+1; // نعدلها بحيث: الأحد=0 ... السبت=6
        $dayOfMonth = $today->day; // 1 - 31

        $this->info("توليد مهام لليوم: {$today->toDateString()}");

        $assignments = TaskAssignment::with('days')->get();

        foreach ($assignments as $assignment) {
            $shouldGenerate = false;

            switch ($assignment->recurrence_type) {
                case 'daily':
                    $shouldGenerate = true;
                    break;

                case 'weekly':
                    $shouldGenerate = $assignment->days->contains('day_of_week', $dayOfWeek);
                    break;

                case 'monthly':
                    $shouldGenerate = $assignment->days->contains('day_of_month', $dayOfMonth);
                    break;
            }

            if ($shouldGenerate) {
                $exists = TaskOccurrence::where('task_assignment_id', $assignment->id)
                    ->whereDate('date', $today)
                    ->exists();

                if (!$exists) {
                    TaskOccurrence::create([
                        'task_assignment_id' => $assignment->id,
                        'date' => $today,
                        'is_generated' => true,
                    ]);

                    $this->info("✅ تم إنشاء occurrence للمهمة {$assignment->id}");
                }
            }
        }

        $this->info("انتهى التوليد.");
    }
}
