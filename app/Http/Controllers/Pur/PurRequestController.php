<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurItem;
use App\Models\PurMainGroup;
use App\Models\PurRequest;
use App\Models\PurSupGroup;
use Illuminate\Http\Request;

class PurRequestController extends Controller
{
    public function index()
    {

        $requests = PurRequest::with('requestItems.item')->latest()->paginate(10);
        return view('purchase.requests.index', compact('requests'));
    }

    public function create()
    {

        $items = PurItem::with('units')->get();
        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();

        return view('purchase.requests.create', compact('items','groups','sections'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'note' => 'nullable|string',
            'employee_id' => 'nullable|integer',
            'request_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id|array',
            'items.*.request_count|array',
        ]);


        $purchaseRequest = PurchaseRequest::create($request->only('note', 'employee_id', 'request_date'));

        foreach ($data['items'] as $item) {

            if (!is_null( $item['request_count'])){

                $purchaseRequest->requestItems()->create([
                    'item_id' => $item['item_id'],
                    'request_count' => $item['request_count'],
                    'unit_id' => $item['unit_id'],
                ]);
            }
        }

        return redirect()->route('purchase_requests.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }


    public function show(PurchaseRequest $purchaseRequest)
    {
        // تحميل الأصناف والعلاقات
        $purchaseRequest->load('requestItems.item'); // item = علاقة في موديل RequestItem

        return view('purchase_requests.show', compact('purchaseRequest'));
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        $items = Item::all();
        $groups =Group::all();
        $sections=Section::all();
        $purchaseRequest->load('requestItems.unit','requestItems.item.units' );
        return view('purchase_requests.edit', compact('purchaseRequest', 'items','groups','sections'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $data = $request->validate([
            'note' => 'nullable|string',
            'request_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'nullable|exists:items,id',
            'items.*.request_count' => 'nullable|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
        ]);
        $purchaseRequest->update($request->only('note', 'employee_id', 'request_date'));
        $purchaseRequest->requestItems()->delete();
        $validItems = collect($data['items'])->filter(function ($item) {
            return isset($item['request_count']) && $item['request_count'] > 0;
        });

        foreach ($validItems as $item) {
            $purchaseRequest->requestItems()->create($item);
        }


        return redirect()->route('purchase_requests.index')->with('success', 'تم تعديل الطلب بنجاح');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->requestItems()->delete();
        $purchaseRequest->delete();

        return redirect()->route('purchase_requests.index')->with('success', 'تم حذف الطلب بنجاح');
    }

    public function confirm(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->requestItems()->update(['is_confirmed' => 1]);

        return redirect()->route('purchase_requests.index')->with('success', 'تم اعتماد الطلب بنجاح');
    }
    public function deconfirm(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->requestItems()->update(['is_confirmed' =>0]);

        return redirect()->route('purchase_requests.index')->with('success', 'تم الغاء اعتماد الطلب بنجاح');
    }
    public function confirmItem($id)
    {

        try {
            $requestItem = RequestItem::findOrFail($id);
            $requestItem->update([
                'is_confirmed' => $requestItem->is_confirmed==1?0:1,
                'confirmed_at' => now(), // إضافة وقت التأكيد إذا كنت بحاجة لذلك
                'confirmed_by' => auth()->id() // تسجيل المستخدم الذي أكد العنصر
            ]);

            return back()->with('success', 'تم تأكيد العنصر بنجاح');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'العنصر غير موجود');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التأكيد: ' . $e->getMessage());
        }
    }
}
