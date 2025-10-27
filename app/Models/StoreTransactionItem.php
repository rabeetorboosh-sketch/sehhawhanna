<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTransactionItem extends Model
{
    use HasFactory;
    protected $table ='store_transactions_items';
    protected $fillable = [
        'store_transactions_id',
        'product_id',
        'product_unit_id',
        'count',
    ];

    /**
     * العملية الرئيسية
     */
    public function transaction()
    {
        return $this->belongsTo(StoreTransaction::class, 'store_transactions_id');
    }

    /**
     * المنتج المرتبط
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * الوحدة المرتبطة بالمنتج
     */
    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'product_unit_id');
    }
}
