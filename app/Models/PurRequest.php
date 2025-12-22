<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurRequest extends Model
{
    protected $table = 'pur_requests';
    protected $fillable = [
        'note',
        'employee_id',
        'user_id',
        'request_date',
        'brunch_id',
    ];


    public function requestItems()
    {
        return $this->hasMany(PurRequestItem::class, 'pur_request_id');
    }


    public function purchases()
    {
        return $this->hasMany(PurPurchase::class, 'pur_request_id');
    }
}
