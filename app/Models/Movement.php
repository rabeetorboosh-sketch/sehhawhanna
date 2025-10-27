<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'direction',
        'stores_number',
    ];

    /**
     * العمليات المخزنية المرتبطة بهذا النوع من الحركة
     */
    public function storeTransactions()
    {
        return $this->hasMany(StoreTransaction::class, 'movement_id');
    }
}
