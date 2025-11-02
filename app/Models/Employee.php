<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'nationality',
        'age',
        'phone',
        'email',
        'id_number',
        'id_expiry_date',
        'branch_id',
        'type_id',
    ];

    // علاقة الموظف بالمجموعة الرئيسية
    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public  function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
    public  function store()
    {
        return $this->hasone(Store::class, 'employee_id');

    }
    public  function stores()
    {
        return $this->hasMany(Store::class, 'employee_id');

    }
    public  function customers()
    {
        return $this->hasMany(Customer::class, 'employee_id');

    }

    // علاقة الموظف بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
