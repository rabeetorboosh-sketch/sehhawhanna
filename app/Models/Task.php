<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'description',
        'branch_id',
    ];
    public function assignments()
    {
        return $this->morphMany(TaskAssignment::class, 'task', 'task_type', 'task_id');
    }

    public  function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
