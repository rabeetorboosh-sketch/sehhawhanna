<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'problem_id',
        'assigned_by',
        'assigned_to',
        'assignment_type',
        'note',
        'status',
        'branch_id',
    ];

    // المشكلة


    // الشخص الذي أسندها
    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by');
    }

    // الشخص الذي أُسندت إليه
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    // الفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
