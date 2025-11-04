<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class StoreMovementsReportsController extends Controller
{
    public  function index()
    {
        $movements =Movement::all();
        return view('reports.movements.index',compact('movements'));
    }
    public function byOperationDetail($id =null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ])->where('movement_id', $id ?? 2);

        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }




        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();
        if (request('summary')) {
            return $this->byOperationSummary($transactions,$id);
        }
        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint='byOperationDetailPrint';
        $title= '-> حسب العملية -تحليلي '.$operation->name;

        return view('reports.movements.by_operation_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
        ));
    }
    public function byOperationDetailPrint($id =null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ])->where('movement_id', $id ?? 2);

        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }




        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();

        if (request('summary')) {
            return $this->byOperationSummaryPrint($transactions,$id);
        }
        $operation = Movement::findOrFail($id ?? 2);

        return view('reports.movements.print.by_operation_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products'
        ));
    }

    public function byOperationSummaryPrint($transactions,$id =null){
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $summary = $transactions->flatMap(function ($transaction) {
            return $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->item?->name ?? '-',
                    'store_from' => $transaction->FromStore?->id,
                    'store_to' => $transaction->ToStore?->id,
                    'count' => $item->count,
                ];
            });
        })
            ->groupBy('product_id')
            ->map(function ($items) {
                $productName = $items->first()['product_name'];
                $totalCount = $items->sum('count');
                $storeCount = collect($items)->pluck('store_from')
                    ->merge(collect($items)->pluck('store_to'))
                    ->filter()
                    ->unique()
                    ->count();

                return [
                    'product_name' => $productName,
                    'total_count' => $totalCount,
                    'store_count' => $storeCount,
                ];
            })
            ->values();

        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint='byOperationDetailPrint';
        $title= '-> حسب العملية - اجمالي '.$operation->name;
        return view('reports.movements.print.by_operation_summary', compact('summary'  ,'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
        ));
    }
    public function byStoreDetail($id = 3)
    {


        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')? Store::where('id',request('store_id'))->get():Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items' => function ($q) {
                if (request('product_id')) {
                    $q->where('product_id', request('product_id'));
                }
                $q->with(['product.item', 'unit.unit']);
            },

        ]);
        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }

        // فلترة حسب المخزن (من أو إلى)
        if (request('store_id')) {
            $storeId = request('store_id');
            $query->where(function ($q) use ($storeId) {
                $q->where('from_store_id', $storeId)
                    ->orWhere('to_store_id', $storeId);
            });
        }

        // فلترة حسب التاريخ

        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $filterstores=Store::all();
        $transactions = $query->get();
        if (request('summary')) {
            return $this->byStoreSummary($transactions);
        }
        $operation = Movement::findOrFail($id ?: 2);
$url='byStoreDetail';
        $title='-> حسب المستودع - تحليلي';
        $movements=Movement::all();

        return view('reports.movements.by_store_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'url',
            'filterstores',
            'employees',
            'movements',
            'title',
            'products'
        ));
    }

    public function byOperationSummary($transactions,$id)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $summary = $transactions->flatMap(function ($transaction) {
            return $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->item?->name ?? '-',
                    'store_from' => $transaction->FromStore?->id,
                    'store_to' => $transaction->ToStore?->id,
                    'count' => $item->count,
                ];
            });
        })
            ->groupBy('product_id')
            ->map(function ($items) {
                $productName = $items->first()['product_name'];
                $totalCount = $items->sum('count');
                $storeCount = collect($items)->pluck('store_from')
                    ->merge(collect($items)->pluck('store_to'))
                    ->filter()
                    ->unique()
                    ->count();

                return [
                    'product_name' => $productName,
                    'total_count' => $totalCount,
                    'store_count' => $storeCount,
                ];
            })
            ->values();

        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint='byOperationDetailPrint';
        $title= '-> حسب العملية - اجمالي '.$operation->name;
        return view('reports.movements.by_operation_summary', compact('summary'  ,'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
             ));
    }

    public function byStoreDetailPrint($id = 3)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')? Store::where('id',request('store_id'))->get():Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items' => function ($q) {
                if (request('product_id')) {
                    $q->where('product_id', request('product_id'));
                }
                $q->with(['product.item', 'unit.unit']);
            },
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب المخزن (من أو إلى)
        if (request('store_id')) {
            $storeId = request('store_id');
            $query->where(function ($q) use ($storeId) {
                $q->where('from_store_id', $storeId)
                    ->orWhere('to_store_id', $storeId);
            });
        }

        // فلترة حسب التاريخ

        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $filterstores=Store::all();
        $transactions = $query->get();
        if (request('summary')) {
            return $this->byStoreSummaryPrint($transactions);
        }
        $operation = Movement::findOrFail($id ?: 2);
$url='byStoreDetail';
        return view('reports.movements.print.by_store_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'url',
            'filterstores',
            'employees',
            'products'
        ));
    }
    public function byStoreSummary($transactions,$id=null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')
            ? Store::where('id', request('store_id'))->get()
            : Store::all();

        // تجميع الإجماليات حسب المخزن والصنف والوحدة
        $summary = [];
        foreach ($transactions as $transaction) {


            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                // 1️⃣ من مخزن (خارج)
                if ($transaction->FromStore) {
                    $storeName = $transaction->FromStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['out'] += $item->count;
                }

                // 2️⃣ إلى مخزن (داخل)
                if ($transaction->ToStore) {


                    $storeName = $transaction->ToStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {

                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0
                        ];

                    }
                    $summary[$storeName][$productName][$unitName]['in'] += $item->count;


                }


            }

        }

        $url='byStoreDetail';
        $title='-> حسب المستودع - اجمالي';
        $filterstores=Store::all();
        $movements=Movement::all();
        return view('reports.movements.by_store_summary', compact(
            'stores',
            'summary',
            'stores',
            'transactions',
            'users',
            'filterstores',
            'url',
            'employees',
            'title',
            'movements',
            'products'
        ));
    }
    public function byStoreSummaryPrint($transactions,$id=null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')
            ? Store::where('id', request('store_id'))->get()
            : Store::all();

        // تجميع الإجماليات حسب المخزن والصنف والوحدة
        $summary = [];
        foreach ($transactions as $transaction) {


            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                // 1️⃣ من مخزن (خارج)
                if ($transaction->FromStore) {
                    $storeName = $transaction->FromStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['out'] += $item->count;
                }

                // 2️⃣ إلى مخزن (داخل)
                if ($transaction->ToStore) {


                    $storeName = $transaction->ToStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {

                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0
                        ];

                    }
                    $summary[$storeName][$productName][$unitName]['in'] += $item->count;


                }


            }

        }

        $url='byStoreDetail';
        $filterstores=Store::all();
        return view('reports.movements.print.by_store_summary', compact(
            'stores',
            'summary',
            'stores',
            'transactions',
            'users',
            'filterstores',
            'url',
            'employees',
            'products'
        ));
    }

    public function byProductDetail( $id = null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items' => function ($q) {
                if (request('product_id')) {
                    $q->where('product_id', request('product_id'));
                }
                $q->with(['product.item', 'unit.unit']);
            },
        ]);
        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب العملية
        if ($id) {
            $query->where('movement_id', $id);
        }

        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }


        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();
        if (request('summary')) {
            return $this->byProductSummary($transactions);
        }
        $grouped = [];
        $filterProductId = request('product_id');

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {

                // إذا تم تمرير صنف معين، نعرضه فقط
                if ($filterProductId && $item->product_id != $filterProductId) {
                    continue;
                }

                $productName = $item->product?->item?->name ?? 'غير محدد';
                $grouped[$productName][] = [
                    'transaction' => $transaction,
                    'item' => $item,
                ];
            }
        }

        $operation = Movement::find($id);


$url='byProductDetail';
        $urlPrint='byProductDetailPrint';
        $title='-> حسب الصنف - تحليلي';
        $movements=Movement::all();

        return view('reports.movements.by_item_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products',
            'url',
            'urlPrint',
            'title',
            'movements',
            'grouped'
        ));
    }
    public function byProductDetailPrint( $id = null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items' => function ($q) {
                if (request('product_id')) {
                    $q->where('product_id', request('product_id'));
                }
                $q->with(['product.item', 'unit.unit']);
            },
        ]);
        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب العملية
        if ($id) {
            $query->where('movement_id', $id);
        }
        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب التاريخ

        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();
        if (request('summary')) {
            return $this->byProductSummaryPrint($transactions);
        }
        $grouped = [];
        $filterProductId = request('product_id');

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {

                // إذا تم تمرير صنف معين، نعرضه فقط
                if ($filterProductId && $item->product_id != $filterProductId) {
                    continue;
                }

                $productName = $item->product?->item?->name ?? 'غير محدد';
                $grouped[$productName][] = [
                    'transaction' => $transaction,
                    'item' => $item,
                ];
            }
        }

        $operation = Movement::find($id);


$url='byProductDetail';
        $title='-> حسب الصنف - تحليلي';
        return view('reports.movements.print.by_item_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products',
            'url',
            'title',
            'grouped'
        ));
    }

    public function byProductSummary($transactions,$id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();


        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = 0;
                }

                $summary[$productName][$unitName] += $item->count;
            }
        }
        $url='byProductDetail';
        $urlPrint='byProductDetailPrint';
        $title='-> حسب الصنف - اجمالي';
        $movements=Movement::all();

        return view('reports.movements.by_item_summary', compact('summary',
            'stores',
            'users',
            'employees',
            'products',
            'title',
            'urlPrint',
            'movements',
            'url'
        ));
    }
    public function byProductSummaryPrint($transactions,$id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();


        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = 0;
                }

                $summary[$productName][$unitName] += $item->count;
            }
        }
        $url='byProductDetail';
        $title='-> حسب الصنف - اجمالي';
        return view('reports.movements.print.by_item_summary', compact('summary',
            'stores',
            'users',
            'employees',
            'products',
            'title',
        'url'
        ));
    }


    public function byEmployeeDetail($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee.item',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ]);
        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب التاريخ

        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();

        if (request('summary')) {
            return $this->byEmployeeSummary($transactions);
        }
        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $title='-> حسب المستخدم - تحليلي';
        $movements=Movement::all();
        return view('reports.movements.by_employee_detail', compact(
            'operation',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'urlPrint',
            'movements',

            'products'
        ));
    }
    public function byEmployeeDetailPrint($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();

        $query = StoreTransaction::with([
            'user',
            'employee.item',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ]);
        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }
        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }
         if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }

        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }


        $transactions = $query->get();

        if (request('summary')) {
            return $this->byEmployeeSummaryPrint($transactions);
        }
        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byEmployeeDetail';
        $title='-> حسب المستخدم - تحليلي';
        return view('reports.movements.print.by_employee_detail', compact(
            'operation',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'products'
        ));
    }


    public function byEmployeeSummary($transactions)
{
    $users = User::all();
    $employees = Employee::with('item')->get();
    $products = Product::all();
    $stores = Store::all();
    $url = 'byEmployeeDetail';

    // نجمع حسب الموظف
    $grouped = $transactions->groupBy('employee_id');
    $summary = [];

    foreach ($grouped as $employeeId => $employeeTransactions) {
        $employeeName = $employeeTransactions->first()->employee?->item?->name ?? 'بدون اسم موظف';

        // نجيب كل الأصناف التي تعامل معها الموظف (من خلال items)
        $allItems = $employeeTransactions->flatMap->items;

        // نجمع حسب الصنف
        $itemsGrouped = $allItems->groupBy(function ($item) {
            return $item->product?->item?->name ?? 'صنف غير معروف';
        });

        foreach ($itemsGrouped as $itemName => $itemsSet) {
            // نحدد العمليات الداخلة والخارجة لهذا الصنف
            $inTransactions = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->to_store_id && $t->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
            });

            $outTransactions = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->from_store_id && $t->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
            });

            $totalIn = $inTransactions->flatMap->items
                ->filter(fn($i) => ($i->product?->item?->name ?? '') === $itemName)
                ->sum('count');

            $totalOut = $outTransactions->flatMap->items
                ->filter(fn($i) => ($i->product?->item?->name ?? '') === $itemName)
                ->sum('count');

            $transactionCount = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->items->contains(function ($i) use ($itemName) {
                    return ($i->product?->item?->name ?? '') === $itemName;
                });
            })->count();

            $summary[] = [
                'employee_name' => $employeeName,
                'item_name' => $itemName,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'transaction_count' => $transactionCount,
            ];
        }
    }
    $title='-> حسب المستخدم - اجمالي';
    $urlPrint = 'byEmployeeDetailPrint';
    $movements=Movement::all();

    return view('reports.movements.by_employee_summary', compact('summary',
        'employees',
        'transactions',
        'users',
        'url',
        'stores',
        'title',
        'movements',
        'urlPrint',
        'products'));
}
    public function byEmployeeSummaryPrint($transactions)
{
    $users = User::all();
    $employees = Employee::with('item')->get();
    $products = Product::all();
    $stores = Store::all();
    $url = 'byEmployeeDetail';

    // نجمع حسب الموظف
    $grouped = $transactions->groupBy('employee_id');
    $summary = [];

    foreach ($grouped as $employeeId => $employeeTransactions) {
        $employeeName = $employeeTransactions->first()->employee?->item?->name ?? 'بدون اسم موظف';

        // نجيب كل الأصناف التي تعامل معها الموظف (من خلال items)
        $allItems = $employeeTransactions->flatMap->items;

        // نجمع حسب الصنف
        $itemsGrouped = $allItems->groupBy(function ($item) {
            return $item->product?->item?->name ?? 'صنف غير معروف';
        });

        foreach ($itemsGrouped as $itemName => $itemsSet) {
            // نحدد العمليات الداخلة والخارجة لهذا الصنف
            $inTransactions = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->to_store_id && $t->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
            });

            $outTransactions = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->from_store_id && $t->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
            });

            $totalIn = $inTransactions->flatMap->items
                ->filter(fn($i) => ($i->product?->item?->name ?? '') === $itemName)
                ->sum('count');

            $totalOut = $outTransactions->flatMap->items
                ->filter(fn($i) => ($i->product?->item?->name ?? '') === $itemName)
                ->sum('count');

            $transactionCount = $employeeTransactions->filter(function ($t) use ($itemName) {
                return $t->items->contains(function ($i) use ($itemName) {
                    return ($i->product?->item?->name ?? '') === $itemName;
                });
            })->count();

            $summary[] = [
                'employee_name' => $employeeName,
                'item_name' => $itemName,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'transaction_count' => $transactionCount,
            ];
        }
    }
    $title='-> حسب المستخدم - اجمالي';
    return view('reports.movements.print.by_employee_summary', compact('summary',
        'employees',
        'transactions',
        'users',
        'url',
        'stores',
        'title',
        'products'));
}




}
