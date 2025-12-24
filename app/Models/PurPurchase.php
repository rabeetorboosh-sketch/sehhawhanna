<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurPurchase extends Model
{
    protected $table = 'pur_purchase';
    protected $fillable = [
        'note',
        'user_id',
        'employee_id',
        'purchase_date',
        'pur_request_id'
    ];

    // العلاقة مع PurchaseItems
    public function purchaseItems()
    {
        return $this->hasMany(PurPurchaseItem::class, 'pur_purchase_id');
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurRequest::class, 'pur_request_id');
    }

    public function intakes()
    {
        return $this->hasMany(PurIntake::class, 'Pur_purchase_id');
    }
}
