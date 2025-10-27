<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'item_id',
        'phone',
        'branch_id',
    ];

    // علاقة المورد بالمجموعة الرئيسية
    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');

    }

    // علاقة المورد بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
