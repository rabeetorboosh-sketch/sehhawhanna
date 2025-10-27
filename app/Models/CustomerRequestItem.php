<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequestItem extends Model
{
    use HasFactory;

    protected $table = 'customers_requests_items';

    protected $fillable = [
        'customer_request_id',
        'product_id',
        'product_unit_id',
        'count',
    ];

    // العلاقات
    public function customerRequest()
    {
        return $this->belongsTo(CustomerRequest::class, 'customer_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'product_unit_id');
    }
}
