<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingItem extends Model
{
    protected  $table ='rating_items';
    protected  $fillable =['rating_id','rating_unit_id','percentage'];

    public function rating()
    {
        return $this->belongsTo(Rating::class, 'rating_id');
    }

    public function ratingUnit()
    {
        return $this->belongsTo(RatingUnit::class, 'rating_unit_id');
    }

}
