<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use HasFactory;

    protected $table = 'customers_requests';

    protected $fillable = [
        'user_id',
        'employee_id',
        'customer_id',
        'sales_rout_id',
        'description',
        'status',
    ];

    // العلاقات
    public function items()
    {
        return $this->hasMany(CustomerRequestItem::class, 'customer_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRout()
    {
        return $this->belongsTo(SalesRout::class);
    }
}
