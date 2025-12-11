<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingAssignmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_assignment_id',
        'employee_id',
        'housing_unit_room_id',
        'start_date',
        'end_date',
        'notes',
    ];

    public function assignment()
    {
        return $this->belongsTo(HousingAssignment::class, 'housing_assignment_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function room()
    {
        return $this->belongsTo(HousingRoom::class, 'housing_unit_room_id');
    }
}
