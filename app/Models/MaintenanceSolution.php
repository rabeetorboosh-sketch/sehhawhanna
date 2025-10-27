<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_request_id',
        'issue_reason',
        'solution_text',
        'time_spent',
        'bad_parts',
        'workshop_name',
        'maintenance_responsible',
        'repair_cost',
        'temporary_solution',
        'has_warranty',
        'warranty_type',
        'warranty_expiry',
        'delivered',
    ];

    // علاقة مع طلب الصيانة
    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class,'maintenance_request_id');
    }
}
