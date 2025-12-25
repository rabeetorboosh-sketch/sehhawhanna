<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurIntake extends Model
{
    protected $table = 'pur_intake';
    protected $fillable = [
        'note',
        'user_id',
        'employee_id',
        'intake_date',
        'pur_purchase_id'
    ];

    // العلاقة مع PurchaseItems
    public function purchaseIntakeItems()
    {
        return $this->hasMany(PurIntakeItem::class, 'pur_intake_id');
    }



}
