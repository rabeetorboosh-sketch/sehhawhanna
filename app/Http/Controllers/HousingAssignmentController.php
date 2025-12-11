<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HousingUnit;
use App\Models\HousingRoom;
use App\Models\HousingAssignment;
use App\Models\HousingAssignmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HousingAssignmentController extends Controller
{
    /**
     * عرض جميع عمليات التسكين
     */
    public function index()
    {
        $assignments = HousingAssignment::with('unit')
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('housing_assignments.index', compact('assignments'));
    }

    /**
     * صفحة إنشاء عملية تسكين
     */
    public function create()
    {
        $units = HousingUnit::with('rooms')->get();
        $employees = Employee::with('item')->get();

        return view('housing_assignments.create', compact('units', 'employees'));
    }

    /**
     * حفظ عملية التسكين
     */
    public function store(Request $request)
    {

        $request->validate([
            'housing_unit_id' => 'required|exists:housing_units,id',
            'assignment_date' => 'required|date',
            'items.*.employee_id' => 'required|exists:employees,id',
            'items.*.housing_unit_room_id' => 'required|exists:housing_rooms,id',
        ]);

        // إنشاء العملية
        $assignment = HousingAssignment::create([
            'housing_unit_id' => $request->housing_unit_id,
            'assigned_by'     => Auth::id(),
            'assignment_date' => $request->assignment_date,
            'notes'           => $request->notes,
        ]);

        // إدخال الموظفين
        foreach ($request->items as $item) {
            HousingAssignmentItem::create([
                'housing_assignment_id' => $assignment->id,
                'employee_id'           => $item['employee_id'],
                'housing_unit_room_id'  => $item['housing_unit_room_id'],
                'start_date'            => $item['start_date'],
                'end_date'              => $item['end_date'],
                'notes'                 => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('housing_assignments.index')
            ->with('success', 'تم إنشاء عملية التسكين بنجاح');
    }

    /**
     * عرض عملية التسكين
     */
    public function show($id)
    {
        $assignment = HousingAssignment::with([
            'unit',
            'items.employee.item',
            'items.room'
        ])->findOrFail($id);

        return view('housing_assignments.show', compact('assignment'));
    }

    /**
     * صفحة التعديل
     */
    public function edit($id)
    {
        $assignment = HousingAssignment::with(['items', 'items.employee.item', 'items.room'])->findOrFail($id);

        $units = HousingUnit::with('rooms')->get();
        $employees = Employee::with('item')->get();

        return view('housing_assignments.edit', compact('assignment', 'units', 'employees'));
    }

    /**
     * حفظ التعديلات
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'housing_unit_id' => 'required|exists:housing_units,id',
            'assignment_date' => 'required|date',
            'items.*.employee_id' => 'required|exists:employees,id',
            'items.*.housing_unit_room_id' => 'required|exists:housing_rooms,id',
        ]);

        $assignment = HousingAssignment::findOrFail($id);

        // تحديث البيانات الأساسية
        $assignment->update([
            'housing_unit_id' => $request->housing_unit_id,
            'assignment_date' => $request->assignment_date,
            'notes'           => $request->notes,
        ]);

        // حذف الموظفين السابقين
        HousingAssignmentItem::where('housing_assignment_id', $assignment->id)->delete();

        // إعادة إدخال الموظفين الحاليين
        foreach ($request->items as $item) {
            HousingAssignmentItem::create([
                'housing_assignment_id' => $assignment->id,
                'employee_id'           => $item['employee_id'],
                'housing_unit_room_id'  => $item['housing_unit_room_id'],
                'start_date'            => $item['start_date'],
                'end_date'              => $item['end_date'],
                'notes'                 => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('housing_assignments.index')
            ->with('success', 'تم تحديث عملية التسكين بنجاح');
    }

    public function getRooms($unit_id)
    {
        try {
            $rooms = HousingRoom::where('housing_unit_id', $unit_id)
                ->get()
                ->map(function ($room) {

                    $used = \App\Models\HousingAssignmentItem::where('housing_unit_room_id', $room->id)->count();

                    $room->used_beds = $used;
                    return $room;
                });

            return response()->json($rooms);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ في جلب الغرف: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * حذف عملية التسكين
     */
    public function destroy($id)
    {
        $assignment = HousingAssignment::findOrFail($id);

        HousingAssignmentItem::where('housing_assignment_id', $id)->delete();
        $assignment->delete();

        return redirect()->route('housing_assignments.index')
            ->with('success', 'تم حذف عملية التسكين بنجاح');
    }

    /**
     * API: إرجاع الغرف حسب رقم الوحدة
     */
    public function getUnitRooms($unitId)
    {
        $rooms = HousingRoom::where('housing_unit_id', $unitId)->get();
        return response()->json($rooms);
    }

    public function roomStatus($id)
    {
        try {

            // جلب الغرفة
            $room = HousingRoom::findOrFail($id);

            // حساب عدد الشاغلين الحاليين
            $used = \App\Models\HousingAssignmentItem::where('housing_unit_room_id', $id)
                ->count();

            return response()->json([
                'full' => $used >= $room->bed_count,
                'free_beds' => max(0, $room->bed_count - $used),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطأ في جلب حالة الغرفة: ' . $e->getMessage()
            ], 500);
        }
    }

}
