<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\SalesRout;
use App\Models\SubGroup;
use App\Models\Branch;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 8)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 8);
        })->get();
        $branches = Branch::all();
        $employees=Employee::all();
        $salerouts=SalesRout::all();
        return view('admin.customers.create', compact('mainGroups','subGroups','branches','employees','salerouts'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required',
            'phone'=>'nullable',
            'employee_id'=>'nullable',
            'sales_rout_id'=>'nullable',
            'main_group'=>'required',
            'sub_group'=>'nullable',

        ]);

        $item = Item::create([
            'name'          => $request['name'],
            'type'          => 'Customer',
            'main_group_id' => $request['main_group'],
            'sub_group_id'  => $request['sub_group'],
            'department_id'  =>'8',
            'branch_id'     => 1,
        ]);
        $request['branch_id']=1;
        Customer::create([
            'item_id'=>$item->id,
            'phone'=>$request['phone'],
            'employee_id'=>$request['employee_id'],
            'sales_rout_id'=>$request['sales_rout_id'],
            'branch_id'     => 1,

        ]);

        return redirect()->route('customers.index')->with('success', 'تم الإضافة بنجاح ✅');
    }

    public function edit(Customer $customer)
    {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 8)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 8);
        })->get();
        $branches = Branch::all();
        $employees=Employee::all();
        $salerouts=SalesRout::all();
        return view('admin.customers.edit', compact('customer','mainGroups','subGroups','branches','employees','salerouts'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'=>'required',
            'phone'=>'required',
            'employee_id'=>'nullable',
            'sales_rout_id'=>'nullable',
            'main_group_id'=>'required',
            'sub_group_id'=>'nullable',

        ]);

        $customer->item->update([
            'name'          => $request['name'],
            'main_group_id' => $request['main_group_id'],
            'sub_group_id'  => $request['sub_group_id'],
        ]);
        $request['branch_id']=1;
        $customer->update([

            'phone'=>$request['phone'],
            'employee_id'=>$request['employee_id'],
            'sales_rout_id'=>$request['sales_rout_id']
        ]);

        return redirect()->route('customers.index')->with('success', 'تم التعديل بنجاح ✅');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'تم الحذف بنجاح ✅');
    }
}
