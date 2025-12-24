<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurPurchaseItem extends Model
{
    protected $table = 'pur_purchase_items';
    protected $fillable = [
        'is_intake',
        'is_confirmed',
        'pur_purchase_count',
        'pur_purchase_id',
        'pur_item_id',
        'pur_unit_id'
    ];

    public function item()
    {
        return $this->belongsTo(PurItem::class,'pur_item_id');
    }
    public function purchase()
    {
        return $this->belongsTo(PurPurchase::class,'pur_purchase_id');
    }
    public function unit()
    {
        return $this->belongsTo(PurItemUnit::class,'pur_unit_id');
    }
}
