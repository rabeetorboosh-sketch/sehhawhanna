<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name','type','main_group_id','sub_group_id','department_id','branch_id'];

    public function mainGroup()
    {
        return $this->belongsTo(MainGroup::class, 'main_group_id');
    }

    public function subGroup()
    {
        return $this->belongsTo(SubGroup::class, 'sub_group_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');

    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');

    }
    public function units()
    {
        return $this->hasMany(ItemUnit::class, 'item_id');
    }
}
