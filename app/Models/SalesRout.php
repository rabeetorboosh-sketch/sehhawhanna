<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRout extends Model
{
protected  $table ="sales_routs";
protected  $fillable = ['employee_id','name'];

public function employee()
{
  return  $this->belongsTo(Employee::class ,'employee_id');
}
public function customers()
{
  return  $this->hasMany(Customer::class ,'sales_rout_id');
}

}
