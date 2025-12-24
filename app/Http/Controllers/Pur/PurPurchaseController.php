<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurItem;
use App\Models\PurMainGroup;
use App\Models\PurPurchase;
use App\Models\PurRequest;
use App\Models\PurRequestItem;
use App\Models\PurSupGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurPurchaseController extends Controller
{
    public function index()
    {
        $purchases = PurPurchase::with('purchaseItems.item')->latest()->paginate(10);
        return view('purchase.purchase.index', compact('purchases'));
    }

    public function create()
    {

        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();
        $items = PurItem::all();

        return view('purchase.purchase.create', compact('items','sections','groups'));
    }

    public function store(Request $request)
    {


        $data = $request->validate([
            'note' => 'nullable|string',
            'employee_id' => 'nullable|integer',
            'purchase_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.purchase_count' => 'nullable|numeric',
            'items.*.unit_id' => 'nullable|integer',
            'pur_request_id' => 'nullable|string'
        ]);

        $fromRequest = isset($data['request_id']);

        $purchase = PurPurchase::create($request->only('note', 'employee_id', 'purchase_date', 'pur_request_id'));

        foreach ($data['items'] as $item) {
            if(!is_null($item['purchase_count']) and !is_null($item['unit_id'] )) {
                $purchase->purchaseItems()->create([
                    'pur_item_id' => $item['item_id'],
                    'pur_purchase_count' => $item['purchase_count'],
                    'pur_unit_id' => $item['unit_id'],
                ]);

                if ($fromRequest) {

                    PurRequestItem::where('pur_item_id', $item['item_id'])
                        ->where('pur_request_id', $request->request_id)
                        ->update(['is_purchased' => 1]);
                }
            }
        }

        return redirect()->route('purchase_purchase.index')->with('success', 'تم إنشاء مشترياتك بنجاح');
    }


    public function show($id)
    {
        $purchase =PurPurchase::findOrFail($id);

        $purchase->load('purchaseItems.item','purchaseItems.unit');

        return view('purchase.purchase.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = PurPurchase::with('purchaseItems')->findOrFail($id);

        $sections = PurMainGroup::all();
        $groups   = PurSupGroup::all();
        $items    = PurItem::with('units')->get();

        // تجهيز بيانات العناصر الحالية لتسهيل عرضها
        $oldItems = $purchase->purchaseItems
            ->keyBy('pur_item_id');

        return view('purchase.purchase.edit', compact(
            'purchase',
            'sections',
            'groups',
            'items',
            'oldItems'
        ));
    }


    public function update(Request $request, $id)
    {
        $purchase = PurPurchase::findOrFail($id);

        $data = $request->validate([
            'note' => 'nullable|string',
            'employee_id' => 'nullable|integer',
            'purchase_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.purchase_count' => 'nullable|numeric',
            'items.*.unit_id' => 'nullable|integer',
        ]);

        // تحديث رأس الفاتورة
        $purchase->update(
            $request->only('note', 'employee_id', 'purchase_date')
        );

        // حذف التفاصيل القديمة
        $purchase->purchaseItems()->delete();

        // إعادة حفظ التفاصيل
        foreach ($data['items'] as $item) {
            if (!is_null($item['purchase_count']) && !is_null($item['unit_id'])) {
                $purchase->purchaseItems()->create([
                    'pur_item_id'        => $item['item_id'],
                    'pur_purchase_count'=> $item['purchase_count'],
                    'pur_unit_id'        => $item['unit_id'],
                ]);
            }
        }

        return redirect()
            ->route('purchase_purchase.index')
            ->with('success', 'تم تعديل فاتورة المشتريات بنجاح');
    }


    public function destroy( $id)
    {
        $purchase =PurPurchase::findOrFail($id);
        $purchase->purchaseItems()->delete();
        $purchase->delete();
        return redirect()->route('purchase_purchase.index')->with('success', 'تم حذف الطلب بنجاح');
    }
    public function confirm($id)
    {
        $purchase =PurPurchase::findOrFail($id);
        $purchase->update(['is_confirmed' => 1]);
        return redirect()->route('purchase_purchase.index')->with('success', 'تم اعتماد الطلب بنجاح');
    }

    public function buy( $id)
    {
        $purchaseRequest =PurRequest::findOrFail($id);
        $purchaseRequest->load('requestItems.item','requestItems.unit');

        $items = PurItem::all();
        $purchaseRequest->load(['requestItems' => function($query) {
            $query->where('is_confirmed', 1)->with('item.units','unit');
        }]);
        $preItems = DB::table('pur_purchase_items')
            ->select('pur_purchase_items.pur_item_id' ,DB::raw('SUM(pur_purchase_items.pur_purchase_count) as purchase_count'))
            ->join('pur_purchase', 'pur_purchase.id', '=', 'pur_purchase_items.pur_purchase_id')
            ->where('pur_purchase.pur_request_id', $purchaseRequest->id)
            ->groupBy('pur_purchase_items.pur_item_id');

        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();
        return view('purchase.purchase.buy', compact('items','purchaseRequest','preItems','sections','groups'));
    }

}
