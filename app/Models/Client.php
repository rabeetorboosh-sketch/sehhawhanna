<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'phone',
        'main_group_id',
        'sub_group_id',
        'branch_id',
    ];

    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');

    }

    // علاقة العميل بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
