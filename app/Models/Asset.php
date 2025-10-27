<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'item_id',
        'usage_date',
        'lifetime',
        'id_number',
        'description',
        'branch_id',
    ];

public  function item()
{
    return $this->belongsTo(Item::class, 'item_id');
}
    // علاقة الموظف بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
