<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyControlItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dailyControl_id',
        'control_unit_id',
        'item_id',
        'causer_id',
        'description',
        'is_correct',
        'branch_id',
    ];

    // وحدة الرقابة
    public function controlUnit()
    {
        return $this->belongsTo(ControlUnit::class, 'control_unit_id');
    }
    public function assignments()
    {
        return $this->morphMany(TaskAssignment::class, 'task', 'task_type', 'task_id');
    }
    public function dailyControl()
    {
        return $this->belongsTo(DailyControl::class, 'dailyControl_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    // المتسبب (موظف)
    public function causer()
    {
        return $this->belongsTo(Employee::class, 'causer_id');
    }

    // الفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'DlyCtrlItem');
    }
}
