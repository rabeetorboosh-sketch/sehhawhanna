<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurIntakeItem extends Model
{
    protected $table = 'pur_intake_items';
    protected $fillable = [
        'is_load',
        'is_confirmed',
        'pur_intake_count',
        'pur_intake_id',
        'pur_item_id',
        'pur_unit_id'

    ];

    public function item()
    {
        return $this->belongsTo(PurItem::class,'pur_item_id');
    }
    public function purchaseIntake()
    {
        return $this->belongsTo(PurIntake::class,'intake_id');
    }
    public function unit()
    {
        return $this->belongsTo(PurItemUnit::class,'pur_unit_id');
    }
}
