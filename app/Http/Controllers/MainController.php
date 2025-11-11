<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\IssueType;
use App\Models\SystemMovement;
use Illuminate\Http\Request;

class MainController extends Controller
{
   public  function tester()
   {
       $employees=Employee::all();
       $selectedClient =Customer::all();
       $clients =  Customer::all();

       $ths=['#','الاسم'];
       $add_url='#';
       $header='انواع المشاكل';
       $trs = IssueType::all(['id','name'])->toArray();
       return view('tester',compact('ths','add_url','header','trs','employees','selectedClient','clients'));
   }

    public function SystemMovementIndex(Request $request)
    {
        $query = SystemMovement::query();

        // إذا لم يتم تحديد التاريخ، استخدم تاريخ اليوم
        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $request->merge(['from_date' => now()->toDateString()]);
        }

        // 🔹 فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔹 فلترة حسب رقم الفاتورة
        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }

        // 🔹 فلترة حسب التاريخ
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('modified_at', [
                $request->from_date,
                $request->to_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('modified_at', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('modified_at', '<=', $request->to_date);
        }

        // 🔹 ترتيب من الأحدث إلى الأقدم
        $movements = $query->orderBy('modified_at', 'desc')->paginate(20);

        // 🔹 المستخدمون لقائمة الفلترة
        $users = \App\Models\User::orderBy('name')->get();

        // 🔹 تمرير البيانات للواجهة
        return view('admin.system_movements.index', compact('movements', 'users'));
    }


}
