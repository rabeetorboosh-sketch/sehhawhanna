<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreMovement extends Model
{
    protected $fillable = ['item_id','branch_id','reference_number','quantity','type','user_id','store_id','item_unit_id','direction'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'transaction');
    }
}
