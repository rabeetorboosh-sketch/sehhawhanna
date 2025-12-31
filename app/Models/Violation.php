<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Violation extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'violation_id',
        'violations_type',
        'sent_to',
        'note',
    ];

    protected $casts = [
        'sent_to' => 'array', // يحول الـ JSON إلى مصفوفة تلقائياً
    ];

    // علاقة مع المستخدم الذي أنشأ المخالفة
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة مع الموظف المخالف
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // إذا كنت ستستخدم Morph (اختياري بناءً على تصميمك)
    public function violation_source(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'violations_type', 'violation_id');
    }
}
