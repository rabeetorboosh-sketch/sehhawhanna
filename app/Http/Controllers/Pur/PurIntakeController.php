<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurIntake;
use App\Models\PurIntakeItem;
use App\Models\PurItem;
use App\Models\PurMainGroup;
use App\Models\PurPurchase;
use App\Models\PurPurchaseItem;
use App\Models\PurSupGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurIntakeController extends Controller
{
    public function index()
    {
        $purchaseIntakes = PurIntake::with('purchaseIntakeItems.item')->latest()->paginate(10);
        return view('purchase.intake.index', compact('purchaseIntakes'));
    }

    public function create()
    {

        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();
        $items = PurItem::all();

        return view('purchase.intake.create', compact('items','sections','groups'));
    }

    public function store(Request $request)
    {


        $data = $request->validate([
            'note' => 'nullable|string',
            'employee_id' => 'nullable|integer',
            'intake_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id|array',
            'items.*.intake_count|array',
            'items.*.unit_id|array',
            'pur_purchase_id'=> 'nullable|string'
        ]);



        $fromrequest=isset($data['pur_purchase_id']);

        $purchaseIntake = PurIntake::create($request->only('note', 'employee_id', 'intake_date','pur_purchase_id'));

        foreach ($data['items'] as $item) {

            if (!is_null( $item['intake_count']) and !is_null($item['unit_id'])){

                $purchaseIntake->purchaseIntakeItems()->create([
                    'pur_item_id' => $item['item_id'],
                    'pur_intake_count' => $item['intake_count'],
                    'pur_unit_id' => $item['unit_id'],

                ]);
                if($fromrequest){
                    PurPurchaseItem::where('pur_item_id', $item['item_id'])->where('pur_purchase_id',$request->request_id)
                        ->update(['is_intake' => 1]);}
            }
        }

        return redirect()->route('intake.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }
    public function show($id)
    {
        $purchaseIntake=PurIntake::find($id);
        $purchaseIntake->load('purchaseIntakeItems.item','purchaseIntakeItems.unit');

        return view('purchase.intake.show', compact('purchaseIntake'));
    }

    public function edit(PurIntake $intake)
    {
        $sections = PurMainGroup::all();
        $groups   = PurSupGroup::all();
        $items    = PurItem::with('units')->get();

        // العناصر المسجلة مسبقاً في الاستلام
        $oldItems = $intake->purchaseIntakeItems
            ->keyBy('pur_item_id');

        return view('purchase.intake.edit', compact(
            'intake',
            'items',
            'sections',
            'groups',
            'oldItems'
        ));
    }

    public function update(Request $request, PurIntake $intake)
    {
        $data = $request->validate([
            'note' => 'nullable|string',
            'employee_id' => 'nullable|integer',
            'intake_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.intake_count' => 'nullable|numeric',
            'items.*.unit_id' => 'nullable|integer',
        ]);

        // تحديث رأس الاستلام
        $intake->update(
            $request->only('note','employee_id','intake_date')
        );

        // حذف التفاصيل القديمة
        $intake->purchaseIntakeItems()->delete();

        // إعادة الإدخال
        foreach ($data['items'] as $item) {
            if (!empty($item['intake_count']) && !empty($item['unit_id'])) {
                $intake->purchaseIntakeItems()->create([
                    'pur_item_id'      => $item['item_id'],
                    'pur_intake_count' => $item['intake_count'],
                    'pur_unit_id'      => $item['unit_id'],
                ]);
            }
        }

        return redirect()
            ->route('intake.index')
            ->with('success','تم تعديل الاستلام بنجاح');
    }


    public function destroy( $id)
    {
        $purchaseIntake =PurIntake::find($id);
        $purchaseIntake->purchaseIntakeItems()->delete();
        $purchaseIntake->delete();
        return redirect()->route('intake.index')->with('success', 'تم حذف الطلب بنجاح');
    }
    public function confirm($id)
    {
        $purchaseIntake=PurIntake::find($id);
        $purchaseIntake->update(['is_confirmed' => 1]);
        return redirect()->route('intake.index')->with('success', 'تم اعتماد الطلب بنجاح');
    }

    public function buy( $id)
    {
        $purchase=PurPurchase::findOrFail($id);
        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();

        $items = PurItem::all();
        $purchase->load('purchaseItems.item')->with('item.units','unit');;
        $preItems = DB::table('pur_intake_items')
            ->select('pur_intake_items.pur_item_id' ,DB::raw('SUM(pur_intake_items.pur_intake_count) as intake_count'))
            ->join('pur_intake', 'pur_intake.id', '=', 'pur_intake_items.pur_intake_id')
            ->where('pur_intake.pur_purchase_id', $purchase->id)
            ->groupBy('pur_intake_items.pur_item_id');


        return view('purchase.intake.buy', compact('items','purchase','preItems','groups','sections'));
    }
}
