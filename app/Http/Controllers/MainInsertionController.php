<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeType;
use App\Models\IssueType;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class MainInsertionController extends Controller
{
   public function IssueTypeIndex()
   {

       $issueTypes = IssueType::all();
       return view('admin.issueType.index',compact('issueTypes'));

   }
   public  function IssueTypeAdd( )
   {
    return view('admin.issueType.add');
   }
    public function IssueTypeCreate(Request $request)
    {

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);

        IssueType::create([
            'name'      => $validated['name'],
            'color'     => $validated['color'] ?? '#000000', // لون افتراضي إذا ما اختاره
            'branch_id' => 1, // ثابت
        ]);

        // رجوع مع رسالة نجاح
        return redirect()->route('issuesType.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }
    public  function IssueTypeEdit(IssueType $issueType )
    {
        return view('admin.issueType.Edit',compact('issueType'));
    }
    public function IssueTypeUpdate(Request $request)
    {
$issueType=IssueType::find($request->id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);
        $issueType->update([
            'name'      => $validated['name'],
            'color'     => $validated['color'] ?? '#000000', // لون افتراضي إذا ما اختاره
            'branch_id' => 1, // ثابت
        ]);
        return redirect()->route('issuesType.edit',$request->id)->with('success', 'تم التعديل بنجاح ✅');
    }
    public function IssueTypeDelete(int $id)
    {

        $issueType = IssueType::findOrFail($id);

        $issueType->delete();

        return redirect()->route('issuesType.index')->with('success', 'تم الحذف بنجاح ✅');
    }



    ////-////-////-///-/-/-/-/-/-/-//-/-/-/-/
    ///



    public function EmployeeTypeIndex()
    {
        $employeeTypes = EmployeeType::all();
        return view('admin.employeeType.index', compact('employeeTypes'));
    }

    public function EmployeeTypeAdd()
    {
        return view('admin.employeeType.add');
    }

    public function EmployeeTypeCreate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        EmployeeType::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('employeeType.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }

    public function EmployeeTypeEdit(EmployeeType $employeeType)
    {
        return view('admin.employeeType.edit', compact('employeeType'));
    }

    public function EmployeeTypeUpdate(Request $request)
    {
        $employeeType = EmployeeType::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $employeeType->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('employeeType.edit', $request->id)->with('success', 'تم التعديل بنجاح ✅');
    }

    public function EmployeeTypeDelete(int $id)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->delete();

        return redirect()->route('employeeType.index')->with('success', 'تم الحذف بنجاح ✅');
    }
    public function EmployeeTypeShow(int $id)
    {

        $employeeType = EmployeeType::findOrFail($id);

        return view('admin.employeeType.show',compact('employeeType'));
    }


    ///
    ////-////-////-///-/-/-/-/-/-/-//-/-/-/-/


    public function MainGroupIndex($department="")
    {

        if ($department=="")
        $mainGroups = MainGroup::all();
        else
        $mainGroups = MainGroup::where('department_id',$department)->get();
        return view('admin.mainGroup.index',compact('mainGroups','department'));

    }
    public  function MainGroupAdd($department="")
    {
        if ($department=="")
        $departments=Department::all();
        else
            $departments=Department::where('id',$department)->get();

        return view('admin.mainGroup.add',compact('departments'));
    }
    public function MainGroupCreate(Request $request)
    {

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'department' => 'required|string|max:50',
        ]);

        MainGroup::create([
            'name'      => $validated['name'],
            'department_id'     => $validated['department'] , // لون افتراضي إذا ما اختاره
            'branch_id' => 1, // ثابت
        ]);

        // رجوع مع رسالة نجاح
        return redirect()->route('mainGroup.add', $validated['department'] )->with('success', 'تمت الإضافة بنجاح ✅');
    }
    public  function MainGroupEdit(MainGroup $mainGroup,$department="" )
    {
        if ($department=="")
        $departments =Department::all();
        else
            $departments =Department::where('id',$department??'')->get();

        return view('admin.mainGroup.edit',compact('mainGroup','departments'));
    }
    public function MainGroupUpdate(Request $request,$id)
    {
        $mainGroup=MainGroup::find($id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'department' => 'nullable|string|max:50',
        ]);
        $mainGroup->update([
            'name'      => $validated['name'],
            'department_id'     => $validated['department']  , // لون افتراضي إذا ما اختاره
            'branch_id' => 1, // ثابت
        ]);
        return redirect()->route('mainGroup.edit',$id)->with('success', 'تم التعديل بنجاح ✅');
    }
    public function MainGroupDelete(int $id ,$department)
    {

        $mainGroup = MainGroup::findOrFail($id);

        $mainGroup->delete();

        return redirect()->route('mainGroup.index',$department??'')->with('success', 'تم الحذف بنجاح ✅');
    }
    public function byDepartment($departmentId)
    {
        return MainGroup::where('department_id', $departmentId)->get();
    }

    //-///-///-/-/---/-/-/-/-/--/-/---/-/-/---//


    public function SubGroupindex($department="")
    {
        if ($department=="" )
            $subGroups = SubGroup::with('mainGroup')->get();
        else{
            $subGroups = SubGroup::whereHas('mainGroup', function($q) use ($department) {
                $q->where('department_id', $department);
            })->get();
        }

        return view('admin.subGroup.index', compact('subGroups','department'));
    }

    public function SubGroupadd($department="")
    {
        if ($department=="")
        $mainGroups = MainGroup::all();
        else
            $mainGroups = MainGroup::where('department_id',$department)->get();


        return view('admin.subGroup.add', compact('mainGroups','department'));
    }

    public function SubGroupcreate(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'required|integer|exists:main_groups,id',
        ]);

        SubGroup::create([
            'name'          => $validated['name'],
            'main_group_id' => $validated['main_group'],
        ]);

        return redirect()->route('subGroup.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }

    public function SubGroupedit(SubGroup $subGroup ,$department="" )
    {
        if ($department=="")
            $mainGroups = MainGroup::all();
        else
            $mainGroups = MainGroup::where('department_id',$department)->get();


        return view('admin.subGroup.edit', compact('subGroup', 'mainGroups'));
    }

    public function SubGroupupdate(Request $request, $id)
    {
        $subGroup = SubGroup::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'nullable|integer|exists:main_groups,id',
        ]);

        $subGroup->update([
            'name'          => $validated['name'],
            'main_group_id' => $validated['main_group'],
        ]);
        $mainGroup =MainGroup::find( $validated['main_group']);

        return redirect()->route('subGroup.index',$mainGroup->department_id)->with('success', 'تم التعديل بنجاح ✅');
    }

    public function SubGroupdelete($id,$department)
    {
        $subGroup = SubGroup::findOrFail($id);
        $subGroup->delete();
        return redirect()->route('subGroup.index',$department??'')->with('success', 'تم الحذف بنجاح ✅');
    }
}
