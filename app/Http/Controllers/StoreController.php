<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Store;
use App\Models\Branch;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('branch')->get();
        return view('admin.store.warehouse.index', compact('stores'));
    }

    public function create()
    {
        $branches = Branch::all();
        $employees = Employee::all();
        return view('admin.store.warehouse.create', compact('branches','employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'nullable|string|max:255',
            'location'  => 'nullable|string|max:255',
            'employee_id'  => 'nullable',
            'branch_id' => 'required',
        ]);

        Store::create($request->all());

        return redirect()->route('stores.index')->with('success', 'تمت إضافة المخزن بنجاح ✅');
    }

    public function edit(Store $store)
    {
        $branches = Branch::all();
        $employees = Employee::all();
        return view('admin.store.warehouse.edit', compact('store', 'branches','employees'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'nullable|string|max:255',
            'location'  => 'nullable|string|max:255',
            'employee_id'  => 'nullable ',
            'branch_id' => 'required ',
        ]);

        $store->update($request->all());

        return redirect()->route('stores.index')->with('success', 'تم تحديث بيانات المخزن بنجاح ✅');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'تم حذف المخزن بنجاح 🗑️');
    }
}
