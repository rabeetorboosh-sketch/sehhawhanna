<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurMainGroup;
use Illuminate\Http\Request;

class PurMainGroupController extends Controller
{
    public function index( )
    {


            $mainGroups = PurMainGroup::all();

        return view('purchase.mainGroup.index',compact('mainGroups',));

    }
    public  function create( )
    {
        return view('purchase.mainGroup.add');
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
        ]);

        PurMainGroup::create([
            'name'      => $validated['name'],
            'branch_id' => 1, // ثابت
        ]);

        // رجوع مع رسالة نجاح
        return redirect()->route('purMainGroup.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }
    public  function edit( $mainGroup  )
    {
        $mainGroup =PurMainGroup::findOrFail($mainGroup);

        return view('purchase.mainGroup.edit',compact('mainGroup'));
    }
    public function update(Request $request,$id)
    {
        $mainGroup=PurMainGroup::find($id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
        ]);
        $mainGroup->update([
            'name'      => $validated['name'],
            'branch_id' => 1,
        ]);
        return redirect()->route('purMainGroup.edit',$id)->with('success', 'تم التعديل بنجاح ✅');
    }
    public function delete(int $id ,$department)
    {

        $mainGroup = PurMainGroup::findOrFail($id);

        $mainGroup->delete();

        return redirect()->route('purMainGroup.index',$department??'')->with('success', 'تم الحذف بنجاح ✅');
    }
}
