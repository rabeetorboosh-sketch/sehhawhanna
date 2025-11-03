<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use App\Models\RatingUnit;
use Illuminate\Http\Request;

class RatingUnitsController extends Controller
{
    /**
     * عرض جميع الوحدات
     */
    public function index()
    {
        $units = RatingUnit::all();
        return view('admin.rating_units.index', compact('units'));
    }

    /**
     * عرض صفحة إنشاء وحدة جديدة
     */
    public function create()
    {
        $employeeTypes=EmployeeType::all();
        return view('admin.rating_units.create',compact('employeeTypes'));
    }

    /**
     * حفظ الوحدة الجديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'multiply' => 'required|numeric',
            'type_id' => 'nullable',
        ]);

        RatingUnit::create($request->only('name', 'multiply','type_id'));

        return redirect()->route('rating_units.index')->with('success', 'تمت إضافة الوحدة بنجاح');
    }

    /**
     * عرض صفحة تعديل وحدة
     */
    public function edit($id)
    {
        $unit = RatingUnit::findOrFail($id);
        $employeeTypes=EmployeeType::all();
        return view('admin.rating_units.edit', compact('unit','employeeTypes'));
    }

    /**
     * تحديث بيانات الوحدة
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'multiply' => 'required|numeric',
            'type_id' => 'type_id',
        ]);

        $unit = RatingUnit::findOrFail($id);
        $unit->update($request->only('name', 'multiply'.'type_id'));

        return redirect()->route('rating_units.index')->with('success', 'تم تحديث الوحدة بنجاح');
    }

    /**
     * حذف وحدة
     */
    public function destroy($id)
    {
        $unit = RatingUnit::findOrFail($id);
        $unit->delete();

        return redirect()->route('rating_units.index')->with('success', 'تم حذف الوحدة بنجاح');
    }
}
