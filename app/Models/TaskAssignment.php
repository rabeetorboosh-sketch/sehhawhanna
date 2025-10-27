<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'task_type',
        'employee_id',
        'assigned_at',
        'due_date',
        'status',
        'recurrence_type',
    ];

    /**
     * المهمة المرتبطة
     */
    public function task()
    {
        return $this->morphTo(__FUNCTION__, 'task_type', 'task_id');
    }
    /**
     * الموظف المرتبط
     */

    public function receipt()
    {
        return $this->hasMany(TaskReceipt::class, 'task_assignment_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        if ($this->due_date == null) {
            return false;
        }

        $firstReceipt = $this->receipt()->orderBy('created_at')->first();

        // إذا ما في استلام، نعتبره متجاوز إذا التاريخ الحالي فات
        if (!$firstReceipt) {
            return now()->gt($this->due_date);
        }

        // إذا الاستلام تم بعد due_date
        return $firstReceipt->created_at->gt($this->due_date);
    }

    public function overdueDiff(): ?array
    {
        if ($this->due_date == null) {
            return null;
        }

        $dueDate = Carbon::parse($this->due_date);

        $firstReceipt = $this->receipt()->orderBy('created_at')->first();
      $compareTime = $firstReceipt?->created_at ?? now();


        $diffInHours = $dueDate->floatDiffInHours($compareTime, false) * -1;


        $hours = (int) floor($diffInHours);


        $minutes = (int) round(($diffInHours - $hours) * 60);

        return [
            'hours' => $hours,
            'minutes' => $minutes
        ];
    }





    /**
     * أيام التكرار
     */
    public function days()
    {
        return $this->hasMany(TaskAssignmentDay::class);
    }

    public function occurrences()
    {
        return $this->hasMany(TaskOccurrence::class);
    }
}
