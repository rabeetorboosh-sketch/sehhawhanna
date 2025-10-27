<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportItem extends Model
{
    use HasFactory;

    protected $fillable = [
            'item_no',
        'item_type',
        'report_id',
        'control_unit_id',
        'user_control_unit',
        'causer_id',
        'issue_description',
        'response_status',
        'branch_id',
    ];

    // علاقة مع البلاغ
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class ,'item_no');

    }

    // علاقة مع نوع المشكلة
    public function controlUnit()
    {
        return $this->belongsTo(ControlUnit::class, 'control_unit_id');
    }
    public function assignments()
    {
        return $this->morphMany(TaskAssignment::class, 'task', 'task_type', 'task_id');
    }

    // علاقة مع المتسبب (موظف أو عميل أو مورد حسب تصميمك)
    public function causer()
    {
        return $this->belongsTo(Employee::class, 'causer_id');
    }

    // علاقة مع الفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }



    public function media()
    {
        return $this->hasMany(Media::class, 'item_id') ->where('type', 'ReportItem');
    }
}
