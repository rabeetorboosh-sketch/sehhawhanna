<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubGroup extends Model
{
    protected $fillable = ['name','main_group_id'];

    public function mainGroup()
    {
        return $this->belongsTo(MainGroup::class, 'main_group_id');
    }

}
