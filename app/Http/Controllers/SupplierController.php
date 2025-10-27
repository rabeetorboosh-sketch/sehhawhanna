<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('item')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 9)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 9);
        })->get();
        return view('admin.suppliers.create', compact('mainGroups', 'subGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'main_group_id' => 'required|exists:main_groups,id',
            'sub_group_id' => 'nullable|exists:sub_groups,id',
        ]);

        DB::transaction(function () use ($request ) {
            $item = Item::create([
                'name'          => $request['name'],
                'type'          => 'Supplier',
                'main_group_id' => $request['main_group_id'],
                'sub_group_id'  => $request['sub_group_id'],
                'department_id'  =>'9',
                'branch_id'     => 1,
            ]);

            Supplier::create([
                'item_id' => $item->id,
                'phone' => $request['phone'],
                'branch_id'=>1
            ]);
        });

        return redirect()->route('suppliers.index')->with('success', 'تم إضافة المورد بنجاح ✅');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 9)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 9);
        })->get();

        return view('admin.suppliers.edit', compact('supplier', 'mainGroups', 'subGroups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'main_group_id' => 'required|exists:main_groups,id',
            'sub_group_id' => 'nullable|exists:sub_groups,id',
        ]);
        DB::transaction(function () use ($request,$id) {
            $supplier = Supplier::findOrFail($id);
            $supplier->item->update([
                'name' => $request['name'],
                'main_group_id' => $request['main_group_id'],
                'sub_group_id' => $request['sub_group_id'],
            ]);
            $supplier->update([
                'phone' => $request->phone,
            ]);
        });
        return redirect()->route('suppliers.index')->with('success', 'تم تعديل بيانات المورد ✅');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'تم حذف المورد ✅');
    }
}
