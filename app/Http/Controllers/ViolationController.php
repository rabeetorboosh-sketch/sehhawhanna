<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\User;
use App\Models\Employee;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = Violation::with(['creator', 'employee'])->latest()->paginate(10);
        return view('violations.index', compact('violations'));
    }

    /**
     * دالة عرض صفحة "إنشاء مخالفة جديدة"
     */
    public function create()
    {
        // نحتاج جلب الموظفين والمستخدمين لعرضهم في القائمة المنسدلة (Dropdown)
        $employees = Employee::all();
        $users = User::all();

        return view('violations.create', compact('employees', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|exists:users,id',
            'employee_id'     => 'required|exists:employees,id',
            'violation_id'    => 'required',
            'violations_type' => 'required',
            'sent_to'         => 'nullable|array',
            'note'            => 'nullable|string',
        ]);

        Violation::create($validated);

        return redirect()->route('violations.index')->with('success', 'تم إضافة المخالفة بنجاح');
    }

    /**
     * دالة عرض صفحة "تعديل مخالفة موجودة"
     */
    public function edit(Violation $violation)
    {
        $employees = Employee::all();
        $users = User::all();

        // نمرر المتغير $violation لتعبيئة الحقول بالبيانات القديمة
        return view('violations.edit', compact('violation', 'employees', 'users'));
    }

    public function update(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'sent_to'         => 'nullable|array',
            'note'            => 'nullable|string',
        ]);

        $violation->update($validated);

        return redirect()->route('violations.index')->with('success', 'تم تحديث البيانات');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();
        return redirect()->route('violations.index')->with('success', 'تم الحذف');
    }
}
