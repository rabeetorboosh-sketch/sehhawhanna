<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'employee_id',
        'report_id',
        'issue_text',
        'issue_type_id',
        'status',
    ];

    // علاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function report()
    {
        return $this->belongsTo(ReportItem::class);
    }

    public function issueType()
    {
        return $this->belongsTo(IssueType::class);
    }
}
