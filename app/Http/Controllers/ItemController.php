<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\MainGroup;
use App\Models\Product;
use App\Models\StoreTransaction;
use App\Models\SubGroup;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $product = Product::with('item')->get();
        $units = Unit::all();
        return view('admin.products.index', compact('product'));
    }

    public function create()
    {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 1)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 1);
        })->get();
        $units = Unit::all();
        return view('admin.products.create', compact('mainGroups', 'subGroups','units'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|max:50',
            'description' => 'nullable',
            'main_group_id' => 'required',
            'sub_group_id' => 'nullable',
            'units*' => 'required',
        ]);

        DB::transaction(function () use ($request ) {
            $item = Item::create([
                'name'          => $request['name'],
                'type'          => 'Product',
                'main_group_id' => $request['main_group_id'],
                'sub_group_id'  => $request['sub_group_id'],
                'department_id'  =>'1',
                'branch_id'     => 1,
            ]);

             Product::create([
                'item_id' => $item->id,
                'description' => $request->description,
                'code' => $request->code,
                'branch_id' => 1, // دايمًا 1
            ]);
            if($request->units){
                foreach($request->units as $unitData){
                    $item->units()->create([
                        'unit_id' => $unitData['unit_id'],
                        'package' => $unitData['package'],
                        'is_main' => isset($unitData['is_main']) ? 1 : 0,
                        'branch_id' => 1,
                    ]);
                }
            }
        });







        return redirect()->route('items.index')->with('success', 'تم إضافة الصنف بنجاح ✅');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $mainGroups = MainGroup::all();
        $subGroups = SubGroup::all();
        $units = Unit::all();

        return view('admin.products.edit', compact('product', 'mainGroups', 'subGroups','units'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'description' => 'nullable',
            'main_group_id' => 'required|exists:main_groups,id',
            'sub_group_id' => 'nullable',
            'units.*.unit_id' => 'required',
            'units.*.package' => 'required|min:1'
        ]);
        DB::transaction(function () use ($request,$id) {
            $product = Product::findOrFail($id);
            $product->item->update([
                'name' => $request['name'],
                'main_group_id' => $request['main_group_id'],
                'sub_group_id' => $request['sub_group_id'],
            ]);
            $product->update([
                'description' => $request->description,
                'code' => $request->code,
            ]);

            $product->item->units()->delete();

            if($request->units){
                foreach($request->units as $unitData){
                    $product->item->units()->create([
                        'unit_id' => $unitData['unit_id'],
                        'package' => $unitData['package'],
                        'is_main' => isset($unitData['is_main']) ? 1 : 0,
                        'branch_id' => 1,
                    ]);
                }
            }

        });



        // تحديث الوحدات: احذف القديمة وأعد إضافتها

        return redirect()->route('items.index')->with('success', 'تم تعديل بيانات الصنف ✅');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->item->delete();
        $product->delete();
        return redirect()->route('items.index')->with('success', 'تم حذف الصنف ✅');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // جلب جميع الحركات المتعلقة بالمنتج
        $transactions = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items' => function ($q) use ($id) {
                $q->where('product_id', $id)
                    ->with(['product.item', 'unit.unit']);
            },
        ])->orderByDesc('id')->get();

        // حساب الكميات في كل مخزن
        $quantities = collect();

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                // إذا كانت العملية نقل من مخزن لمخزن آخر
                if ($transaction->from_store_id && $transaction->to_store_id) {
                    // خصم من المخزن المصدر
                    $quantities->push([
                        'store_id' => $transaction->from_store_id,
                        'store_name' => $transaction->FromStore->name ?? 'غير محدد',
                        'unit' => $item->unit->unit->name ?? '—',
                        'count' => -1 * $item->count,
                    ]);
                    // إضافة إلى المخزن المستقبل
                    $quantities->push([
                        'store_id' => $transaction->to_store_id,
                        'store_name' => $transaction->ToStore->name ?? 'غير محدد',
                        'unit' => $item->unit->unit->name ?? '—',
                        'count' => $item->count,
                    ]);
                }
                // إذا كانت عملية إضافة للمخزن فقط
                elseif ($transaction->to_store_id) {
                    $quantities->push([
                        'store_id' => $transaction->to_store_id,
                        'store_name' => $transaction->ToStore->name ?? 'غير محدد',
                        'unit' => $item->unit->unit->name ?? '—',
                        'count' => $item->count,
                    ]);
                }
                // إذا كانت عملية صرف من المخزن فقط
                elseif ($transaction->from_store_id) {
                    $quantities->push([
                        'store_id' => $transaction->from_store_id,
                        'store_name' => $transaction->FromStore->name ?? 'غير محدد',
                        'unit' => $item->unit->unit->name ?? '—',
                        'count' => -1 * $item->count,
                    ]);
                }
            }
        }

        // تجميع الكميات حسب المخزن والوحدة
        $quantities = $quantities
            ->groupBy(fn($q) => $q['store_id'] . '-' . $q['unit'])
            ->map(function ($group) {
                $first = $group->first();
                return (object)[
                    'store_name' => $first['store_name'],
                    'unit' => $first['unit'],
                    'count' => $group->sum('count'),
                ];
            })->values();

        return view('admin.products.show', compact('product', 'transactions', 'quantities'));
    }

}
