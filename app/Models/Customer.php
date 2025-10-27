<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'item_id',
        'phone',
        'employee_id',
        'sales_rout_id',
        'branch_id',
    ];

    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');

    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function sales_rout()
    {
        return $this->belongsTo(SalesRout::class, 'sales_rout_id');
    }
}
