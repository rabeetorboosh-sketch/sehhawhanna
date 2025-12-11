<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HousingUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_code',
        'name',
        'unit_type',
        'total_rooms',
        'total_kitchens',
        'total_bathrooms',
        'status',
        'address',
        'notes',
        'branch_id',
    ];

    public function rooms()
    {
        return $this->hasMany(HousingRoom::class, 'housing_unit_id');
    }
    public function assignments()
    {
        return $this->hasMany(HousingAssignment::class, 'housing_unit_id');
    }

}
