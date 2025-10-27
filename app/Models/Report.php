<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'issue_type_id',
        'status',
        'department_id',
        'branch_id',
    ];

    // علاقة البلاغ بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة البلاغ بنوع البلاغ
    public function reportType()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }

    // علاقة البلاغ بالقسم
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // علاقة البلاغ بالفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // علاقة البلاغ ببنود البلاغات
    public function items()
    {
        return $this->hasMany(ReportItem::class, 'report_id');
    }
}
