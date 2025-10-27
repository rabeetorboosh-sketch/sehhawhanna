<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultVal extends Model
{
 protected $table ='Defaults';
 protected $fillable =['description','value','item'];
}
