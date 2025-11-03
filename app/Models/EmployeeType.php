<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $table = 'employees_types';
    protected $fillable = [
        'name',

    ];
    public function employees() {
        return $this->hasMany(Employee::class, 'type_id');
    }

}
