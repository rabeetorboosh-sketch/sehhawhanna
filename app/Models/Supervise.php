<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervise extends Model
{
    protected  $table ='supervise';
    protected $fillable = [
        'user_id',
        'customer_id',
        'name',
        'employee_id',
        'phone',
        'issue',
        'start_time',
        'is_completed',
        'delay_reason',
        'transferred_to_management',
        'transfer_reason',
        'solution_method',
        'location'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'Supervise');
    }
}
