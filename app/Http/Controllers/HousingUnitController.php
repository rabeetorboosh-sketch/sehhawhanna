<?php

namespace App\Http\Controllers;

use App\Models\HousingRoom;
use App\Models\HousingUnit;
use Illuminate\Http\Request;

class HousingUnitController extends Controller
{
    public function index()
    {
        $housingUnits  = HousingUnit::latest()->paginate(20);
        return view('admin.housing_units.index', compact('housingUnits'));
    }

    public function create()
    {
        return view('admin.housing_units.create');
    }

    public function store(Request $request)
    {


        // التحقق من بيانات الوحدة
        $data = $request->validate([
            'unit_code' => 'required|string',
            'name' => 'nullable|string',
            'unit_type' => 'nullable|string',
            'total_kitchens' => 'nullable|integer',
            'total_bathrooms' => 'nullable|integer',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'rooms' => 'array', // مجموعة الغرف
            'rooms.*.room_name' => 'required|string',
            'rooms.*.bed_count' => 'required|integer',
            'rooms.*.room_type' => 'nullable|string',
            'rooms.*.has_bathroom' => 'nullable'
        ]);
        // حفظ بيانات الوحدة السكنية
        $unit = HousingUnit::create([
            'unit_code'        => $data['unit_code'],
            'name'             => $data['name'] ?? null,
            'unit_type'        => $data['unit_type'] ?? null,
            'total_kitchens'   => $data['total_kitchens'],
            'total_bathrooms'  => $data['total_bathrooms'],
            'total_rooms'      => count($data['rooms'] ?? []),
            'status'           => $data['status'] ?? 'available',
            'address'          => $data['address'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'branch_id'        => $data['branch_id'] ?? null,
        ]);

        // حفظ الغرف المرتبطة
        if (!empty($data['rooms'])) {
            foreach ($data['rooms'] as $room) {
                HousingRoom::create([
                    'housing_unit_id' => $unit->id,
                    'room_name'       => $room['room_name'],
                    'bed_count'       => $room['bed_count'],
                    'room_type'       => $room['room_type'] ?? null,
                    'has_bathroom'    => $room['has_bathroom'] ?? 0,
                    'notes'           => $room['notes'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'تمت إضافة الوحدة السكنية مع الغرف بنجاح');
    }
    public function edit($id)
    {
        $unit = HousingUnit::findOrFail($id);
        return view('admin.housing_units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = HousingUnit::findOrFail($id);

        $data = $request->validate([
            'unit_code' => 'required|string',
            'name' => 'nullable|string',
            'unit_type' => 'nullable|string',
            'total_kitchens' => 'nullable|integer',
            'total_bathrooms' => 'nullable|integer',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'rooms' => 'array',
            'rooms.*.room_name' => 'required|string',
            'rooms.*.bed_count' => 'required|integer',
            'rooms.*.room_type' => 'nullable|string',
            'rooms.*.has_bathroom' => 'nullable'
        ]);

        $unit->update([
            'unit_code' => $data['unit_code'],
            'name' => $data['name'] ?? null,
            'unit_type' => $data['unit_type'] ?? null,
            'total_kitchens' => $data['total_kitchens'],
            'total_bathrooms' => $data['total_bathrooms'],
            'total_rooms' => count($data['rooms']),
            'address' => $data['address'],
            'notes' => $data['notes'],
        ]);

        // حذف كل الغرف السابقة ثم إعادة إنشاء الغرف
        $unit->rooms()->delete();

        foreach ($data['rooms'] as $room) {
            HousingRoom::create([
                'housing_unit_id' => $unit->id,
                'room_name' => $room['room_name'],
                'bed_count' => $room['bed_count'],
                'room_type' => $room['room_type'] ?? null,
                'has_bathroom' => $room['has_bathroom'] ?? 0,
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث بيانات الوحدة بنجاح');
    }

    public function show($id)
    {
        $unit = HousingUnit::with('rooms')->findOrFail($id);

        return view('admin.housing_units.show', [
            'unit' => $unit
        ]);
    }

    public function destroy($id)
    {
        HousingUnit::findOrFail($id)->delete();

        return redirect()->route('housing_units.index')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }
}
