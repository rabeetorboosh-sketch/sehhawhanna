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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreMovementsController extends Controller
{
    public function index($movement='')
    {
        $query = StoreTransaction::with('items')->orderByDesc('id');

        if ($movement) {
            $query->where('movement_id', $movement);
        }

        $storeTransactions = $query->paginate(20);
        return view('store.storeMovements.index', compact('storeTransactions','movement'));
    }

    // Show the form for creating a new report
    public function create($move='')
    {
        if (!$move){

            return redirect(url()->previous());
        }

        $employees = (Auth::user()->isAdmin())?Employee::all():Employee::where('user_id',Auth::id()) ->get();
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
                'user_id' => 'nullable|integer'
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
        $transaction=StoreTransaction::with('items.unit.unit','media')->findOrFail($id);

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

        $employees = (Auth::user()->isAdmin())
            ? Employee::all()
            : Employee::where('user_id', Auth::id())->get();

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
                'user_id' => 'nullable|integer'
            ]);


            DB::beginTransaction();

            $report = StoreTransaction::findOrFail($id);

            if ($request->direction == 0) {
                $report->update([
                    'user_id' => Auth::id(),
                    'employee_id' => $request->employee_id,
                    'description' => $request->description,
                    'from_store_id' => $request->employee_store_id,
                    'to_store_id' => $request->store_id,
                    'movement_id' => $request->movement_id,
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
                    'status' => 'pending',
                ]);
            }

            // clear old items
            DB::table('store_transactions_items')->where('store_transactions_id', $report->id)->delete();

            // insert new items
            foreach ($request->product_id as $productId) {
                if (!empty($productId['item_count'])) {
                    DB::table('store_transactions_items')->insert([
                        'store_transactions_id' => $report->id,
                        'product_id' => $productId['id'],
                        'product_unit_id' => $productId['unit'],
                        'count' => $productId['item_count'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
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

            return redirect()->route('storeMovements.index',$request->movement_id)->with('success', 'تم تعديل الحركة بنجاح.');

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
