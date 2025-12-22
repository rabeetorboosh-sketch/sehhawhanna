<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurMainGroup extends Model
{
    protected $table='pur_groups';

    protected $fillable = [

        'name',

    ];




    public function itesms()
    {
        return $this->hasMany(PurItem::class,'pur_main_group_id');
    }
}
