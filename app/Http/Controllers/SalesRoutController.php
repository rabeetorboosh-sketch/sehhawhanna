<?php

namespace App\Http\Controllers;

use App\Models\SalesRout;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalesRoutController extends Controller
{
    /**
     * صفحة عرض جميع المسارات
     */
    public function index()
    {

        $salesRouts = SalesRout::with('employee')->get();
        return view('sales_routs.index', compact('salesRouts'));
    }

    /**
     * صفحة عرض نموذج إضافة جديد
     */
    public function create()
    {
        $employees = Employee::all();
        return view('sales_routs.create', compact('employees'));
    }

    /**
     * حفظ المسار الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'name' => 'required|string|max:255',
        ]);

        SalesRout::create($request->only('employee_id', 'name'));

        return redirect()->route('sales_routs.index')
            ->with('success', 'تمت إضافة المسار بنجاح');
    }

    /**
     * صفحة عرض التفاصيل
     */
    public function show($id)
    {
        $salesRout = SalesRout::with('employee','customers')->findOrFail($id);
        return view('sales_routs.show', compact('salesRout'));
    }

    /**
     * صفحة التعديل
     */
    public function edit($id)
    {
        $salesRout = SalesRout::findOrFail($id);
        $employees = Employee::all();
        return view('sales_routs.edit', compact('salesRout', 'employees'));
    }

    /**
     * حفظ التعديلات
     */
    public function update(Request $request, $id)
    {
        $salesRout = SalesRout::findOrFail($id);

        $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'name' => 'required|string|max:255',
        ]);

        $salesRout->update($request->only('employee_id', 'name'));

        return redirect()->route('sales_routs.index')
            ->with('success', 'تم تعديل المسار بنجاح');
    }

    /**
     * حذف المسار
     */
    public function destroy($id)
    {
        $salesRout = SalesRout::findOrFail($id);
        $salesRout->delete();

        return redirect()->route('sales_routs.index')
            ->with('success', 'تم حذف المسار بنجاح');
    }
}
