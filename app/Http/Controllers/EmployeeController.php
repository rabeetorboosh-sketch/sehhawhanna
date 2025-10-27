<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\SubGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employee::with('item','branch')->get();
        return view('admin.employee.index', compact('employees'));
    }

    public function create() {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 4)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 4);
        })->get();;
        $users = User::all();
        return view('admin.employee.create', compact('mainGroups','subGroups','users'));
    }

    public function store(Request $request) {


        DB::transaction(function () use ($request ) {
            $item = Item::create([
                'name'          => $request['name'],
                'type'          => 'Employee',
                'main_group_id' => $request['main_group'],
                'sub_group_id'  => $request['sub_group'],
                'department_id'  =>'4',
                'branch_id'     => 1,
            ]);

            Employee::create([
                'item_id' => $item->id,
                'user_id' => $request['user_id'],
                'nationality' => $request['nationality'],
                'age' => $request['age'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'id_number' => $request['id_number'],
                'id_expiry_date'=> $request['id_expiry_date'],
                'branch_id'=>1
            ]);
        });


        return redirect()->route('employees.index')->with('success','تم إضافة الموظف ✅');
    }

    public function edit($id) {
        $employee = Employee::with('item')->findOrFail($id);
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 4)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 4);
        })->get();
        $users = User::all();
        return view('admin.employee.edit', compact('employee','mainGroups','subGroups','users'));
    }

    public function update(Request $request, $id) {



        DB::transaction(function () use ($request,$id) {
            $employee = Employee::findOrFail($id);
            $employee->item->update([
                'name' => $request['name'],
                'main_group_id' => $request['main_group_id'],
                'sub_group_id' => $request['sub_group_id'],
            ]);
            $employee->update([

                'nationality' => $request['nationality'],
                'age' => $request['age'],
                'user_id' => $request['user_id'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'id_number' => $request['id_number'],
                'id_expiry_date'=> $request['id_expiry_date'],

            ]);
        });



        return redirect()->route('employees.index')->with('success','تم تعديل الموظف ✅');
    }

    public function destroy($id) {
        $employee = Employee::findOrFail($id);
        $employee->item->delete();
        $employee->delete();
        return redirect()->route('employees.index')->with('success','تم حذف الموظف ✅');
    }
}

