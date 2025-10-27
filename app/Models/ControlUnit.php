<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issue_type_id',
        'department_id',
        'main_group_id',
        'sub_group_id',
        'has_photos',
        'daily_control',
        'branch_id',
    ];

    public function issueType()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }

    public function section()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function mainGroup()
    {
        return $this->belongsTo(MainGroup::class, 'main_group_id');
    }

    public function subGroup()
    {
        return $this->belongsTo(SubGroup::class, 'sub_group_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
