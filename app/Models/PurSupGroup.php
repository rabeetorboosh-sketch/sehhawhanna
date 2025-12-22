<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurSupGroup extends Model
{
    protected $table='pur_sup_groups';

    protected $fillable = [

        'name',
        'pur_group_id',

    ];




    public function itesms()
    {
        return $this->hasMany(PurItem::class,'pur_sup_group_id');
    }
    public function mainGroup()
    {
        return $this->belongsTo(PurMainGroup::class, 'pur_group_id');
    }
}
