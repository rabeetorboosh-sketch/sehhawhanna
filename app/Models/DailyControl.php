<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day',
        'branch_id',
    ];

    // المستخدم
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(DailyControlItem::class, 'dailyControl_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
