<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HousingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_unit_id',
        'room_name',
        'bed_count',
        'room_type',
        'has_bathroom',
        'notes',
    ];

    public function unit()
    {
        return $this->belongsTo(HousingUnit::class, 'housing_unit_id');
    }
}
