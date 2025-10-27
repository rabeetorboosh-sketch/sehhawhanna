<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMovement extends Model
{
    use HasFactory;

    protected $table = 'assets_movements';

    protected $fillable = [
        'asset_number',
        'from_item',
        'from_item_type',
        'to_item',
        'to_item_type',
        'movement_datetime',
        'reason',
        'asset_status',
        'movement_destination',
        'user_id',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class ,'asset_number');
    }

    public function fromCustomer()
    {
        return $this->belongsTo(Customer::class, 'from_item');
    }

    public function fromSupplier()
    {
        return $this->belongsTo(Supplier::class, 'from_item');
    }

    public function fromEmployee()
    {
        return $this->belongsTo(Employee::class, 'from_item');
    }

    public function toCustomer()
    {
        return $this->belongsTo(Customer::class, 'to_item');
    }

    public function toSupplier()
    {
        return $this->belongsTo(Supplier::class, 'to_item');
    }

    public function toEmployee()
    {
        return $this->belongsTo(Employee::class, 'to_item');
    }


}
