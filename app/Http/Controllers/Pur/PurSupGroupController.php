<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurMainGroup;
use App\Models\PurSupGroup;
use Illuminate\Http\Request;

class PurSupGroupController extends Controller
{
    public function  index($department="")
    {
        if ($department=="" )
            $subGroups = PurSupGroup::with('mainGroup')->get();
        else{
            $subGroups = PurSupGroup::whereHas('mainGroup', function($q) use ($department) {
                $q->where('department_id', $department);
            })->get();
        }

        return view('purchase.subGroup.index', compact('subGroups','department'));
    }

    public function  add()
    {

            $mainGroups = PurMainGroup::all();

        return view('purchase.subGroup.add', compact('mainGroups'));
    }

    public function  create(Request $request)
    {

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'required|integer|exists:main_groups,id',
        ]);

        PurSupGroup::create([
            'name'          => $validated['name'],
            'pur_group_id' => $validated['main_group'],
        ]);
        return redirect()->route('PurSubGroup.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }

    public function edit(  $subGroup  )
    {

            $subGroup = PurSupGroup::findOrFail($subGroup);
            $mainGroups = PurMainGroup::all();


        return view('purchase.subGroup.edit', compact('subGroup', 'mainGroups'));
    }

    public function  update(Request $request, $id)
    {
        $subGroup = PurSupGroup::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'nullable|integer|exists:main_groups,id',
        ]);

        $subGroup->update([
            'name'          => $validated['name'],
            'main_group_id' => $validated['main_group'],
        ]);
        $mainGroup =PurMainGroup::find( $validated['main_group']);

        return redirect()->route('PurSubGroup.index',$mainGroup->department_id)->with('success', 'تم التعديل بنجاح ✅');
    }

    public function  delete($id)
    {
        $subGroup = PurSupGroup::findOrFail($id);
        $subGroup->delete();
        return redirect()->route('PurSubGroup.index')->with('success', 'تم الحذف بنجاح ✅');
    }
}
