<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'code',
        'description',
        'branch_id',
    ];

    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');

    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
