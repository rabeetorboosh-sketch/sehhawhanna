<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemMovement extends Model
{
    protected $table = 'system_movements';

    protected $fillable = [
        'field_name',     // اسم الحقل الذي تغير
        'old_value',      // القيمة قبل التغيير
        'new_value',      // القيمة بعد التغيير
        'invoice_id',     // رقم الفاتورة
        'invoice_type',   // نوع الفاتورة (مثل: تحميل)
        'user_id',        // رقم المستخدم
        'modified_at',    // تاريخ التعديل
    ];

    public $timestamps = true;

    protected $dates = ['modified_at'];
}
