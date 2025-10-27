<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignmentDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_assignment_id',
        'day_of_week',
        'day_of_month',
    ];

    /**
     * الإسناد المرتبط
     */
    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class, 'task_assignment_id');
    }
}
