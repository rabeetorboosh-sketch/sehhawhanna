<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\Product;
use App\Models\PurItem;
use App\Models\PurMainGroup;
use App\Models\PurSupGroup;
use App\Models\PurUnit;
use App\Models\StoreTransaction;
use App\Models\SubGroup;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurItemController extends Controller
{
    public function index()
    {
        $product = PurItem::get();
        return view('purchase.products.index', compact('product'));
    }

    public function create()
    {
        $mainGroups   = PurMainGroup::all();


        $subGroups    = PurSupGroup::all();
        $units = PurUnit::all();
        return view('purchase.products.create', compact('mainGroups', 'subGroups','units'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|max:50',
            'main_group_id' => 'required',
            'sub_group_id' => 'nullable',
            'units*' => 'required',
        ]);

        DB::transaction(function () use ($request ) {
            $item = PurItem::create([
                'name'          => $request['name'],
                'pur_main_group_id' => $request['main_group_id'],
                'pur_sup_group_id'  => $request['sub_group_id'],
                'code' => $request->code,
                'department_id'  =>'1',
                'branch_id'     => 1,
            ]);


            if($request->units){
                foreach($request->units as $unitData){
                    $item->units()->create([
                        'pur_unit_id' => $unitData['unit_id'],
                        'quantity' => $unitData['package'],
                        'is_main' => isset($unitData['is_main']) ? 1 : 0,
                        'branch_id' => 1,
                    ]);
                }
            }
        });







        return redirect()->route('pur_items.index')->with('success', 'تم إضافة الصنف بنجاح ✅');
    }

    public function edit($id)
    {
        $product = PurItem::findOrFail($id);
        $mainGroups = PurMainGroup::all();
        $subGroups = PurSupGroup::all();
        $units = PurUnit::all();

        return view('purchase.products.edit', compact('product', 'mainGroups', 'subGroups','units'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'main_group_id' => 'required|exists:main_groups,id',
            'sub_group_id' => 'nullable',
            'units.*.unit_id' => 'required',
            'units.*.package' => 'required|min:1'
        ]);
        DB::transaction(function () use ($request,$id) {
            $product = PurItem::findOrFail($id);

            $product->update([
                'name' => $request['name'],
                'pur_main_group_id' => $request['main_group_id'],
                'pur_sup_group_id' => $request['sub_group_id'],
                'code' => $request->code,
            ]);

            $product->units()->delete();

            if($request->units){
                foreach($request->units as $unitData){
                    $product->units()->create([
                        'pur_unit_id' => $unitData['unit_id'],
                        'quantity' => $unitData['package'],
                        'is_main' => isset($unitData['is_main']) ? 1 : 0,
                        'branch_id' => 1,
                    ]);
                }
            }

        });



        // تحديث الوحدات: احذف القديمة وأعد إضافتها

        return redirect()->route('pur_items.index')->with('success', 'تم تعديل بيانات الصنف ✅');
    }

    public function destroy($id)
    {
        $product = PurItem::findOrFail($id);
        $product->units()->delete();
        $product->delete();
        return redirect()->route('pur_items.index')->with('success', 'تم حذف الصنف ✅');
    }

    public function show($id)
    {
        $product = PurItem::findOrFail($id);



        return view('purchase.products.show', compact('product'));
    }
}
