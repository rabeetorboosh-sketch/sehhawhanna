<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Branch;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.units.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        Unit::create([
            'name' => $request['name'],
            'branch_id'=>1

        ]);

        return redirect()->route('units.index')->with('success', 'تمت إضافة الوحدة بنجاح ✅');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',

        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([

            'name' => $request['name'],
            'branch'=>1

        ]);

        return redirect()->route('units.index')->with('success', 'تم تحديث الوحدة بنجاح ✅');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'تم حذف الوحدة بنجاح 🗑️');
    }
}
