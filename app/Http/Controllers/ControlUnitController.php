<?php

namespace App\Http\Controllers;

use App\Models\ControlUnit;
use App\Models\IssueType;
use App\Models\Department;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class ControlUnitController extends Controller
{
    public function index($department="")
    {
        if($department=="")
        $controlUnits = ControlUnit::with('issueType', 'section', 'mainGroup', 'subGroup')->get();
       else
       $controlUnits = ControlUnit::with('issueType', 'section', 'mainGroup', 'subGroup')->where('department_id',$department)->get();

        return view('admin.controlUnit.index', compact('controlUnits','department'));
    }

    public function create($department="")
    {
        $issueTypes = IssueType::all();
        if($department==""){
            $sections = Department::all();
            $mainGroups = MainGroup::all();
            $subGroups = SubGroup::all();
        }
        else{
            $sections = Department::where('id',$department)->get();
            $mainGroups = MainGroup::where('department_id',$department)->get();
            $subGroups  = SubGroup::whereIn('main_group_id', $mainGroups->pluck('id'))->get();
        }



        return view('admin.controlUnit.create', compact('issueTypes', 'sections', 'mainGroups', 'subGroups','department'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'issue_type_id' => 'required|integer',
            'department_id' => 'required|integer',
            'main_group_id' => 'nullable|integer',
            'sub_group_id' => 'nullable|integer',
            'has_photos' => 'nullable',
            'daily_control' => 'nullable',
        ]);

        $data['branch_id'] = 1; // الفرع ثابت

        ControlUnit::create($data);

        return redirect()->route('controlUnit.index',$data['department_id'])->with('success', 'تمت الإضافة بنجاح ✅');
    }

    public function edit(ControlUnit $controlUnit,$department="")
    {
        $issueTypes = IssueType::all();
        if($department==""){
            $sections = Department::all();
            $mainGroups = MainGroup::all();
            $subGroups = SubGroup::all();
        }
        else{
            $sections = Department::where('id',$department)->get();
            $mainGroups = MainGroup::where('department_id',$department)->get();
            $subGroups  = SubGroup::whereIn('main_group_id', $mainGroups->pluck('id'))->get();
        }

        return view('admin.controlUnit.edit', compact('controlUnit', 'issueTypes', 'sections', 'mainGroups', 'subGroups'));
    }

    public function update(Request $request, ControlUnit $controlUnit)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'issue_type_id' => 'required|integer',
            'department_id' => 'required|integer',
            'main_group_id' => 'nullable|integer',
            'sub_group_id' => 'nullable|integer',
            'has_photos' => 'nullable',
            'daily_control' => 'nullable',
        ]);

        $data['branch_id'] = 1;

        $controlUnit->name = $validated['name'];
        $controlUnit->issue_type_id = $validated['issue_type_id'];
        $controlUnit->department_id = $validated['department_id'];
        $controlUnit->main_group_id = $validated['main_group_id'] ?? null;
        $controlUnit->sub_group_id = $validated['sub_group_id'] ?? null;
        $controlUnit->has_photos = $validated['has_photos'] ?? 0;
        $controlUnit->daily_control = $validated['daily_control'] ?? 0;
        $controlUnit->branch_id = 1;

        $controlUnit->save();
        return redirect()->route('controlUnit.index',$validated['department_id'])->with('success', 'تم التعديل بنجاح ✅');
    }

    public function destroy(ControlUnit $controlUnit,$department="")
    {
        $controlUnit->delete();
        return redirect()->route('controlUnit.index',$department)->with('success', 'تم الحذف بنجاح ✅');
    }
}
