<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurRequestItem extends Model
{
    protected $table = 'pur_request_items';
    protected $fillable = [
        'is_factory_intake',
        'is_loaded',
        'is_intake',
        'is_purchased',
        'is_confirmed',
        'pur_request_count',
        'pur_request_id',
        'pur_item_id',
        'pur_unit_id'
    ];

    public function Item()
    {
        return $this->belongsTo(PurItem::class,'pur_item_id');
    }
    public function request()
    {
        return $this->belongsTo(PurRequest::class,'pur_request_id');
    }
    public function unit()
    {
        return $this->belongsTo(PurUnit::class,'pur_unit_id');
    }
}
