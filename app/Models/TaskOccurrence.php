<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskOccurrence extends Model
{
    use HasFactory;

    protected $fillable = ['task_assignment_id', 'date', 'is_generated'];


    public function getDate()
    {
        $thisDate = Carbon::parse($this->date);

// الوقت من تاريخ الاستحقاق
        $dueTime = Carbon::parse($this->assignment?->due_date)->format('H:i:s');

// دمج التاريخ من $thisDate مع الوقت من $dueTime
        $newDate = $thisDate->format('Y-m-d') . ' ' . $dueTime;

// إذا أردت ككائن Carbon:
        $newDateCarbon = Carbon::parse($newDate);

        return $newDateCarbon;
    }

    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class ,'task_assignment_id');
    }

    public function receipt()
    {
        return $this->hasMany(TaskReceipt::class, 'task_occurrence_id');
    }
    public function isOverdue(): bool
    {
        $thisDate = Carbon::parse($this->date);

// الوقت من تاريخ الاستحقاق
        $dueTime = Carbon::parse($this->assignment?->due_date)->format('H:i:s');

// دمج التاريخ من $thisDate مع الوقت من $dueTime
        $newDate = $thisDate->format('Y-m-d') . ' ' . $dueTime;

// إذا أردت ككائن Carbon:
        $newDateCarbon = Carbon::parse($newDate);

        if ($newDateCarbon == null) {
            return false;
        }

        $firstReceipt = $this->receipt()->orderBy('created_at')->first();

        // إذا ما في استلام، نعتبره متجاوز إذا التاريخ الحالي فات
        if (!$firstReceipt) {
            return now()->gt($newDateCarbon);
        }

        // إذا الاستلام تم بعد due_date
        return $firstReceipt->created_at->gt($newDateCarbon);
    }

    public function overdueDiff(): ?array
    {
        $thisDate = Carbon::parse($this->date);

// الوقت من تاريخ الاستحقاق
        $dueTime = Carbon::parse($this->assignment?->due_date)->format('H:i:s');

// دمج التاريخ من $thisDate مع الوقت من $dueTime
        $newDate = $thisDate->format('Y-m-d') . ' ' . $dueTime;

// إذا أردت ككائن Carbon:
        $newDateCarbon = Carbon::parse($newDate);



        if ($newDateCarbon == null) {
            return null;
        }

        $dueDate = Carbon::parse($newDateCarbon);

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
}

