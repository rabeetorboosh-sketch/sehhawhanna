<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurItem extends Model
{
    protected $table='pur_items';

    protected $fillable = [
        'pur_main_group_id',
        'pur_sup_group_id',
        'code',
        'name',
    ];



    public function maingroup()
    {
        return $this->belongsTo(PurMainGroup::class,'pur_main_group_id');
    }
    public function subgroup()
    {
        return $this->belongsTo(PurSupGroup::class,'pur_sup_group_id');
    }
    public function units()
    {
        return $this->hasMany(PurItemUnit::class,'pur_item_id');
    }
}
