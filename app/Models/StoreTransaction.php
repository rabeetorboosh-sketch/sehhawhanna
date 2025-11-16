<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTransaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'employee_id',
        'from_store_id',
        'to_store_id',
        'movement_id',
        'description',
        'status',
        'signature',
    ];

    /**
     * المستخدم الذي أنشأ العملية
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الموظف المرتبط بالعملية
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function empSignature()
    {
        return $this->belongsTo(Employee::class,'signature' ,'signature');
    }

    /**
     * المخزن المرتبط
     */
    public function FromStore()
    {
        return $this->belongsTo(Store::class,'from_store_id');
    }
    public function ToStore()
    {
        return $this->belongsTo(Store::class,'to_store_id');
    }

    /**
     * نوع الحركة (إضافة، صرف، مرتجع...)
     */
    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }

    /**
     * الأصناف المرتبطة بالعملية
     */
    public function items()
    {
        return $this->hasMany(StoreTransactionItem::class, 'store_transactions_id');
    }
    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'transaction');
    }
}
