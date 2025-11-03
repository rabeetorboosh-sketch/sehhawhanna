<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingUnit extends Model
{
    protected  $table ='rating_units';
    protected  $fillable =['multiply','name','type_id'];



}
