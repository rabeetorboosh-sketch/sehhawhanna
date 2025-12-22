<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurItemUnit extends Model
{
    protected  $fillable = ['pur_item_id','pur_unit_id','quantity','symbol','is_default',];

    public function unit()
    {
        return $this->belongsTo(PurUnit::class, 'pur_unit_id');
    }

}
