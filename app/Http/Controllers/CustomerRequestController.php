<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\CustomerRequestItem;
use App\Models\MainGroup;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\SalesRout;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerRequestController extends Controller
{
    /**
     * عرض جميع الطلبات
     */
    public function index()
    {
        $requests = CustomerRequest::with(['customer', 'employee', 'salesRout'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('customers_requests.index', compact('requests'));
    }

    /**
     * عرض صفحة إنشاء طلب جديد
     */
    public function create()
    {
        $users = User::all();
        if (Auth::user()->isAdmin()) {
            $employees = Employee::all();
        } else {
            $employees = Employee::where('user_id', Auth::id())->get();

            if ($employees->isEmpty()) {
                return "
            <div style='text-align: center; margin-top: 50px;'>
                <div class='worn' style='color: red; margin-bottom: 20px;'>
                    هذا المستخدم لم يرتبط بموظف
                </div>
                <a href='javascript:history.back()' style='text-decoration: none; padding: 10px 20px; background-color: #3490dc; color: white; border-radius: 5px;'>
                    عودة للخلف
                </a>
            </div>
        ";
            }
        }



        $customers = Customer::all();
        $salesRouts = SalesRout::all();
        $sections = MainGroup::where('department_id',1)->get();
        // المنتجات مع المجموعات والوحدات
        $products = Product::with(['item.units.unit'])->get();
        $groups = $products->pluck('item.subGroup')->unique()->filter();

        return view('customers_requests.create', compact(
            'users',
            'employees',
            'customers',
            'salesRouts',
            'products',
            'sections',
            'groups'
        ));
    }

    /**
     * حفظ الطلب الجديد في قاعدة البيانات
     */
    public function store(Request $request)
    {


        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sales_rout_id' => 'required|exists:sales_routs,id',
            'description' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء الطلب
            $customerRequest = CustomerRequest::create([
                'user_id' => Auth::id(),
                'employee_id' => $request->employee_id,
                'customer_id' => $request->customer_id,
                'sales_rout_id' => $request->sales_rout_id,
                'description' => $request->description,
                'status' =>  'pending',
            ]);

            // إنشاء العناصر التابعة
            foreach ($request->items as $itemData) {
                if (!isset($itemData['id']) || !isset($itemData['unit']) || !isset($itemData['count'])) {
                    continue;
                }

                CustomerRequestItem::create([
                    'customer_request_id' => $customerRequest->id,
                    'product_id' => $itemData['id'],
                    'product_unit_id' => $itemData['unit'],
                    'count' => $itemData['count'],
                ]);
            }

            DB::commit();
            return redirect()->route('customersRequests.index')
                ->with('success', 'تم حفظ طلب العميل بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل طلب معين
     */
    public function show($id)
    {
        $request = CustomerRequest::with(['items.product', 'customer', 'employee', 'salesRout'])->findOrFail($id);
        return view('customers_requests.show', compact('request'));
    }

    /**
     * عرض صفحة تعديل الطلب
     */
    public function edit($id)
    {
        $requestModel = CustomerRequest::with('items')->findOrFail($id);

        $users = User::all();
        if (Auth::user()->isAdmin()) {
            $employees = Employee::all();
        } else {
            $employees = Employee::where('user_id', Auth::id())->get();

            if ($employees->isEmpty()) {
                return "
            <div style='text-align: center; margin-top: 50px;'>
                <div class='worn' style='color: red; margin-bottom: 20px;'>
                    هذا المستخدم لم يرتبط بموظف
                </div>
                <a href='javascript:history.back()' style='text-decoration: none; padding: 10px 20px; background-color: #3490dc; color: white; border-radius: 5px;'>
                    عودة للخلف
                </a>
            </div>
        ";
            }
        }
        $customers = Customer::all();
        $salesRouts = SalesRout::all();
        $products = Product::with(['item.units.unit'])->get();
        $groups = $products->pluck('item.subGroup')->unique()->filter();
        $sections = MainGroup::where('department_id',1)->get();
        return view('customers_requests.edit', compact(
            'requestModel',
            'users',
            'employees',
            'customers',
            'salesRouts',
            'products',
            'sections',
            'groups'
        ));
    }

    /**
     * تحديث الطلب
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:500',
            'status' => 'in:pending,approved',
            'items' => 'required|array|min:1',
        ]);

        $customerRequest = CustomerRequest::findOrFail($id);

        DB::beginTransaction();
        try {
            $customerRequest->update([
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // حذف العناصر القديمة
            CustomerRequestItem::where('customer_request_id', $customerRequest->id)->delete();

            // إدخال العناصر الجديدة
            foreach ($request->items as $itemData) {
                CustomerRequestItem::create([
                    'customer_request_id' => $customerRequest->id,
                    'product_id' => $itemData['id'],
                    'product_unit_id' => $itemData['unit'],
                    'count' => $itemData['count'],
                ]);
            }

            DB::commit();
            return redirect()->route('customersRequests.index')
                ->with('success', 'تم تحديث الطلب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف الطلب
     */
    public function destroy($id)
    {
        $request = CustomerRequest::findOrFail($id);
        $request->delete();
        return redirect()->route('customersRequests.index')->with('success', 'تم حذف الطلب بنجاح.');
    }

    public function changStatus($id)
    {

        $customerRequest=CustomerRequest::findOrFail($id);
        if ($customerRequest->status=='approved')
            $customerRequest->status='pending';
        else
            $customerRequest->status='approved';

        $customerRequest->save();
        return redirect(url()->previous());
    }
}
