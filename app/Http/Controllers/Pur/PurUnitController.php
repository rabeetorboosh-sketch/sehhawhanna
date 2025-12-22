<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\PurUnit;
use Illuminate\Http\Request;

class PurUnitController extends Controller
{
    public function index()
    {
        $units = PurUnit::all();
        return view('purchase.units.index', compact('units'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('purchase.units.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        PurUnit::create([
            'name' => $request['name'],
            'branch_id'=>1

        ]);

        return redirect()->route('pur_units.index')->with('success', 'تمت إضافة الوحدة بنجاح ✅');
    }

    public function edit($id)
    {
        $unit = PurUnit::findOrFail($id);
        return view('purchase.units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',

        ]);

        $unit = PurUnit::findOrFail($id);
        $unit->update([

            'name' => $request['name'],
            'branch'=>1

        ]);

        return redirect()->route('pur_units.index')->with('success', 'تم تحديث الوحدة بنجاح ✅');
    }

    public function destroy($id)
    {
        $unit = PurUnit::findOrFail($id);
        $unit->delete();

        return redirect()->route('pur_units.index')->with('success', 'تم حذف الوحدة بنجاح 🗑️');
    }
}
