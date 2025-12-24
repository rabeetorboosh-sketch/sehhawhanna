<?php

namespace App\Http\Controllers\Pur;

use App\Http\Controllers\Controller;
use App\Models\PurItem;
use App\Models\PurMainGroup;
use App\Models\PurRequest;
use App\Models\PurRequestItem;
use App\Models\PurSupGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


        $purchaseRequest = PurRequest::create([
            'note' =>$request['note'],
            'user_id'=> Auth::id(),
            'request_date'=>$request['request_date'],
            'brunch_id' =>1]
        );

        foreach ($data['items'] as $item) {

            if (!is_null( $item['request_count'])){

                $purchaseRequest->requestItems()->create([
                    'pur_item_id' => $item['item_id'],
                    'pur_request_count' => $item['request_count'],
                    'pur_unit_id' => $item['unit_id'],
                ]);
            }
        }

        return redirect()->route('purchase_requests.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }
    public function show( $id)
    {


        $purchaseRequest =PurRequest::findOrFail($id);
        // تحميل الأصناف والعلاقات
        $purchaseRequest->load('requestItems.item'); // item = علاقة في موديل RequestItem

        return view('purchase.requests.show', compact('purchaseRequest'));
    }
    public function edit($id)
    {
        $request = PurRequest::with('requestItems')->findOrFail($id);

        $items = PurItem::with('units')->get();
        $sections = PurMainGroup::all();
        $groups = PurSupGroup::all();

        // ترتيب عناصر الطلب الحالية بشكل يسهل التعامل معها في Blade
        $oldItems = $request->requestItems
            ->keyBy('pur_item_id');

        return view(
            'purchase.requests.edit',
            compact('request','items','groups','sections','oldItems')
        );
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'note' => 'nullable|string',
            'request_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.request_count' => 'nullable|numeric',
            'items.*.unit_id' => 'nullable|integer',
        ]);

        $purchaseRequest = PurRequest::findOrFail($id);

        $purchaseRequest->update([
            'note' => $request->note,
            'request_date' => $request->request_date,
        ]);

        // حذف الأصناف القديمة
        $purchaseRequest->requestItems()->delete();

        // إعادة الإدخال
        foreach ($data['items'] as $item) {
            if (!empty($item['request_count'])) {
                $purchaseRequest->requestItems()->create([
                    'pur_item_id' => $item['item_id'],
                    'pur_request_count' => $item['request_count'],
                    'pur_unit_id' => $item['unit_id'],
                ]);
            }
        }

        return redirect()
            ->route('purchase_requests.index')
            ->with('success', 'تم تعديل الطلب بنجاح');
    }

    public function destroy(  $id)
    {

        $purchaseRequest = PurRequest::with('requestItems')->findOrFail($id);

        $purchaseRequest->requestItems()->delete();
        $purchaseRequest->delete();

        return redirect()->route('purchase_requests.index')->with('success', 'تم حذف الطلب بنجاح');
    }

    public function confirm($id)
    {

        $purchaseRequest = PurRequest::with('requestItems')->findOrFail($id);

        $purchaseRequest->requestItems()->update(['is_confirmed' => 1]);

        return redirect()->route('purchase_requests.index')->with('success', 'تم اعتماد الطلب بنجاح');
    }
    public function deconfirm($id)
    {

        $purchaseRequest = PurRequest::with('requestItems')->findOrFail($id);

        $purchaseRequest->requestItems()->update(['is_confirmed' =>0]);

        return redirect()->route('purchase_requests.index')->with('success', 'تم الغاء اعتماد الطلب بنجاح');
    }
    public function confirmItem($id)
    {

        try {
            $requestItem = PurRequestItem::findOrFail($id);
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
