<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\DefaultVal;
use App\Models\Employee;
use App\Models\MainGroup;
use App\Models\Media;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\SubGroup;
use App\Models\SystemMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreMovementsController extends Controller
{


    public function index(Request $request, $movement = '')
    {
        $query = StoreTransaction::with('items', 'user', 'employee', 'fromStore', 'toStore', 'movement')
            ->orderByDesc('id');

        if ($movement) {
            $query->where('movement_id', $movement);
        }

        // فلترة بالموظف
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // فلترة بالتاريخ (اليوم إذا لم يُحدد)
        $date = $request->input('date', now()->toDateString());
        $query->whereDate('created_at', $date);

        $storeTransactions = $query->paginate(20);
        $employees = Employee::with('item')->get();

        return view('store.storeMovements.index', compact('storeTransactions', 'movement', 'date', 'employees'));
    }


    // Show the form for creating a new report
    public function create($move='')
    {
        if (!$move){

            return redirect(url()->previous());
        }

//        $employees = (Auth::user()->isAdmin())?Employee::all():Employee::where('user_id',Auth::id())->get();
        $employees = Employee::all();
        $products = Product::with(['item.subGroup', 'item.mainGroup'])->get();
        $sections = MainGroup::where('department_id',1)->get();
        $groups = SubGroup::whereHas('mainGroup', function($q) {
            $q->where('department_id', 1);
        })->get();
        $branches =Branch::all();
        $stores=Store::all();
        $users=User::all();
            $movement=Movement::find($move??3);
        $default = DefaultVal::where('item', 'main_store')->where('user_id',Auth::id())->first();

        if ($default) {
            $defaults = $default->toArray();
        } else {
            $defaults = []; // أو أي قيمة افتراضية
        }
        return view('store.storeMovements.create', compact('groups','sections','employees','products','stores','branches','users','movement','defaults'));
    }



    public function store(Request $request)
    {

        if(isset($request->set_default) &&$request->set_default==1 ){
            $defult=DefaultVal::where('item','main_store')->where('user_id',Auth::id())->first();
            if ($defult){
                $defult->value=$request->store_id;
                $defult->save();
            }else{
                DefaultVal::create([
                    'item'=>'main_store',
                    'user_id'=>Auth::id(),
                    'value'=>$request->store_id
                ]);
            }

        }

        try {

            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'description' => 'required|string',
                'movement_id' => 'required',
                'product_id' => 'required|array',
                'store_id' => 'nullable',
                'employee_store_id' => 'nullable',
                'user_id' => 'nullable|integer',
                'signature' => 'nullable'
            ]);


            // Start Transaction (to prevent partial inserts)
            DB::beginTransaction();


            if ($request->direction==0){

                $report = StoreTransaction::create([
                    'user_id' => Auth::id(),
                    'employee_id' => $request->employee_id,
                    'description' => $request->description,
                    'from_store_id' => $request->employee_store_id,
                    'to_store_id' =>  $request->store_id,
                    'movement_id' => $request->movement_id,
                    'signature' => $request->signature,
                    'status' => 'pending',
                ]);
            }else{

                $report = StoreTransaction::create([
                    'user_id' => Auth::id(),
                    'employee_id' => $request->employee_id,
                    'description' => $request->description,
                    'from_store_id' =>  $request->store_id,
                    'to_store_id' => $request->employee_store_id,
                    'movement_id' => $request->movement_id,
                    'signature' => $request->signature,
                    'status' => 'pending',
                ]);
            }


            // Insert products
            foreach ($request->product_id as $index => $productId) {
                if (!empty($productId['item_count'])) {
                    DB::table('store_transactions_items')->insert([
                        'store_transactions_id' => $report->id,
                        'product_id' => $productId['id'],
                        'product_unit_id' => $productId['unit'],
                        'count' => $productId['item_count'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

//                    DB::table('store_movements')->insert([
//                        'store_id' => $request->store_id,
//                        'branch_id' => $request->branch_id,
//                        'item_id' => $productId,
//                        'quantity' => $request->item_count[$index],
//                        'direction' => 0, // خروج
//                        'operation_type_id' => 3,
//                        'operation_id' => $report->id,
//                        'user_id' => Auth::id(),
//                        'operation_date' => now(),
//                        'reference_no' => null,
//                        'notes' => $request->description ??'--',
//                        'batch_number' => null,
//                        'source_or_destination' => null,
//                        'created_at' => now(),
//                        'updated_at' => now(),
//                    ]);
                }
            }


            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/transactions', 'public');

                    Media::create([
                        'item_id' => $report->id,
                        'url'     => $path,
                        'type'    => 'transaction',
                    ]);
                }
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('storeMovements.index',$request->movement_id)->with('success', 'Report created successfully.');

        } catch (\Exception $e) {
            // Rollback if there's an error

            DB::rollBack();
            Log::error('Saving error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            // Return with old input and error message
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while saving. Please try again.']);



        }


    }



    public function show( $id)
    {
        $transaction=StoreTransaction::with('items.unit.unit','media','empSignature')->findOrFail($id);

        return view('store.storeMovements.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,approved',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        return redirect()->route('reports.index')->with('success', 'Report status updated successfully.');
    }


    public function edit($id)
    {
        $transaction = StoreTransaction::with(['items.product.item.units', 'employee.store'])->findOrFail($id);

        $employees = Employee::all();
        $products = Product::with(['item.subGroup', 'item.mainGroup', 'item.units'])->get();
        $sections = MainGroup::where('department_id', 1)->get();
        $groups = SubGroup::whereHas('mainGroup', function($q) {
            $q->where('department_id', 1);
        })->get();
        $branches = Branch::all();
        $stores = Store::all();
        $users = User::all();
        $movement = $transaction->movement;
        $default = DefaultVal::where('item', 'main_store')->where('user_id',Auth::id())->first();

        if ($default) {
            $defaults = $default->toArray();
        } else {
            $defaults = []; // أو أي قيمة افتراضية
        }

        return view('store.storeMovements.edit', compact(
            'transaction', 'groups','sections','employees','products',
            'stores','branches','users','movement','defaults'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'description' => 'required|string',
                'movement_id' => 'required',
                'product_id' => 'required|array',
                'store_id' => 'nullable',
                'employee_store_id' => 'nullable',
                'user_id' => 'nullable|integer',
                'signature' => 'nullable'
            ]);

            DB::beginTransaction();

            $report = StoreTransaction::findOrFail($id);

            $data = [
                'employee_id' => $request->employee_id,
                'description' => $request->description,
                'from_store_id' => $request->employee_store_id,
                'to_store_id' => $request->store_id,
                'movement_id' => $request->movement_id,
                'product_id' => $request->product_id,
                'signature' => $request->signature,
            ];

            // سجل تغييرات الحقول العامة
            foreach ($data as $field => $val) {
                if ($field==='product_id')
                    continue;
                $oldValue = is_array($report->$field) ? json_encode($report->$field, JSON_UNESCAPED_UNICODE) : $report->$field;
                $newValue = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;

                if ($oldValue != $newValue) {
                    SystemMovement::create([
                        'field_name'   => $field,
                        'old_value'    => $oldValue,
                        'new_value'    => $newValue,
                        'invoice_id'   => $report->id,
                        'invoice_type' => $report->movement->name,
                        'user_id'      => Auth::id(),
                        'modified_at'  => now(),
                    ]);
                }
            }


            // تحديث بيانات التقرير الأساسية
            if ($request->direction == 0) {
                $report->update([
                    'user_id' => Auth::id(),
                    'employee_id' => $request->employee_id,
                    'description' => $request->description,
                    'from_store_id' => $request->employee_store_id,
                    'to_store_id' => $request->store_id,
                    'movement_id' => $request->movement_id,
                    'signature' => $request->signature,
                    'status' => 'pending',
                ]);
            } else {
                $report->update([
                    'user_id' => Auth::id(),
                    'employee_id' => $request->employee_id,
                    'description' => $request->description,
                    'from_store_id' => $request->store_id,
                    'to_store_id' => $request->employee_store_id,
                    'movement_id' => $request->movement_id,
                    'signature' => $request->signature,
                    'status' => 'pending',
                ]);
            }

            // جلب الأصناف القديمة قبل حذفها
            $oldItems = DB::table('store_transactions_items')
                ->where('store_transactions_id', $report->id)
                ->get();

            // حذف الأصناف القديمة
            DB::table('store_transactions_items')->where('store_transactions_id', $report->id)->delete();

            // إدخال الأصناف الجديدة + تسجيل حركة التعديل لكل صنف
            foreach ($request->product_id as $product) {
                if (!empty($product['item_count'])) {
                    DB::table('store_transactions_items')->insert([
                        'store_transactions_id' => $report->id,
                        'product_id' => $product['id'],
                        'product_unit_id' => $product['unit'],
                        'count' => $product['item_count'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // التحقق من وجود الصنف سابقًا لتسجيل التغيير بدقة
                    $oldItem = $oldItems->firstWhere('product_id', $product['id']);

                    if ($oldItem) {
                        // سجل التغييرات في الكمية أو الوحدة
                        if ($oldItem->count != $product['item_count'] || $oldItem->product_unit_id != $product['unit']) {
                            SystemMovement::create([
                                'field_name'   => 'product_update',
                                'old_value'    => "Product: {$oldItem->product_id}, Count: {$oldItem->count}, Unit: {$oldItem->product_unit_id}",
                                'new_value'    => "Product: {$product['id']}, Count: {$product['item_count']}, Unit: {$product['unit']}",
                                'invoice_id'   => $report->id,
                                'invoice_type' => $report->movement->name,
                                'user_id'      => Auth::id(),
                                'modified_at'  => now(),
                            ]);
                        }
                    } else {
                        // صنف جديد تمت إضافته
                        SystemMovement::create([
                            'field_name'   => 'product_add',
                            'old_value'    => null,
                            'new_value'    => "Product: {$product['id']}, Count: {$product['item_count']}, Unit: {$product['unit']}",
                            'invoice_id'   => $report->id,
                            'invoice_type' => $report->movement->name,
                            'user_id'      => Auth::id(),
                            'modified_at'  => now(),
                        ]);
                    }
                }
            }

            // تحقق من الأصناف التي تم حذفها
            foreach ($oldItems as $oldItem) {
                $exists = collect($request->product_id)->firstWhere('id', $oldItem->product_id);
                if (!$exists) {
                    SystemMovement::create([
                        'field_name'   => 'product_delete',
                        'old_value'    => "Product: {$oldItem->product_id}, Count: {$oldItem->count}, Unit: {$oldItem->product_unit_id}",
                        'new_value'    => null,
                        'invoice_id'   => $report->id,
                        'invoice_type' => $report->movement->name,
                        'user_id'      => Auth::id(),
                        'modified_at'  => now(),
                    ]);
                }
            }

            // الصور
            if ($request->hasFile('images')) {
                $report->media()->delete();
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/transactions', 'public');
                    Media::create([
                        'item_id' => $report->id,
                        'url'     => $path,
                        'type'    => 'transaction',
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('storeMovements.index', $request->movement_id)
                ->with('success', 'تم تعديل الحركة بنجاح وتم تسجيل حركة لكل صنف.');

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء التعديل. حاول مرة أخرى.']);
        }
    }


    public function destroy($id)
    {
        $transaction = StoreTransaction::findOrFail($id);

        // إذا الحركة لها items نحذفهم أولاً
        if ($transaction->items()->count() > 0) {
            $transaction->items()->delete();
        }

        // بعدين نحذف الحركة نفسها
        $transaction->delete();

        return redirect()->route('storeMovements.index')
            ->with('success', 'تم حذف الحركة بنجاح ✅');
    }


}
