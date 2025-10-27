<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'type',
        'url'
    ];

    public function getUrlAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
