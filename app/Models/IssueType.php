<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'branch_id',
    ];

    // علاقة نوع المشكلة بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
