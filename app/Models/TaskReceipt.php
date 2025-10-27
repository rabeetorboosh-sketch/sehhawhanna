<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_assignment_id',
        'task_occurrence_id',
        'employee_id',
        'received_at',
        'completion_percentage',
        'is_completed',
        'solution_method',
        'forwarded_to_management',
        'forward_reason',
        'notes'
    ];

    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class, 'task_assignment_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function occurrence()
    {
        return $this->belongsTo(TaskOccurrence::class, 'task_occurrence_id');
    }
    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'TaskReceipt');
    }

}

