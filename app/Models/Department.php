<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','branch_id'];
    public function controlUnits()
    {
        return $this->hasMany(ControlUnit::class, 'department_id');
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
