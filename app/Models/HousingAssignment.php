<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_unit_id',
        'assigned_by',
        'assignment_date',
        'notes',
    ];

    public function unit()
    {
        return $this->belongsTo(HousingUnit::class ,'housing_unit_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // الموظفون المسكنون في العملية
    public function items()
    {
        return $this->hasMany(HousingAssignmentItem::class);
    }
}

