<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
   protected  $table ='rating';
   protected  $fillable =['item_id','date','user_id'];

    public function items()
    {
        return $this->hasMany(RatingItem::class, 'rating_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


}
