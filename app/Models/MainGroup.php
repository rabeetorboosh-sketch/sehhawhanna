<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainGroup extends Model
{
    protected $fillable = ['name','department_id'];

    public function subGroups()
    {
        return $this->hasMany(SubGroup::class, 'main_group_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'main_group_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'main_group_id');
    }
}
