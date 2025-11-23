<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MainGroup;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\SubGroup;
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
        $main_groups = MainGroup::where('department_id',1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
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

        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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
            if (request('summary')==1)
            return $this->byOperationSummary($transactions,$id);
            if (request('summary')==2)
            return $this->byOperationSummaryBySubGroup($transactions,$id);
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
            'main_groups',
            'sub_groups',
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

        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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


            if (request('summary')==1)
                return $this->byOperationSummaryPrint($transactions,$id);
            if (request('summary')==2)
                return $this->byOperationSummaryBySubGroupPrint($transactions,$id);
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
    public function byOperationSummary($transactions,$id)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        $summary = $transactions->flatMap(function ($transaction) {
            return $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product?->item?->name ?? '-',
                    'main_group_id' => $item->product?->item?->main_group_id,
                    'sub_group_id'  => $item->product?->item?->sub_group_id,
                    'store_from'   => $transaction->FromStore?->id,
                    'store_to'     => $transaction->ToStore?->id,
                    'count'        => $item->count,
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
                $mainGroup = $items->first()['main_group_id'];
                $subGroup = $items->first()['sub_group_id'];

                return [
                    'product_name' => $productName,
                    'main_group_id' => $mainGroup,
                    'sub_group_id' => $subGroup,
                    'total_count' => $totalCount,
                    'store_count' => $storeCount,
                ];
            })
            ->values();

        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint = 'byOperationDetailPrint';
        $title = '-> حسب العملية - اجمالي ' . $operation->name;

        return view('reports.movements.by_operation_summary', compact(
            'summary',
            'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
            'main_groups',
            'sub_groups',
        ));

    }
    public function byOperationSummaryPrint($transactions,$id =null){

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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
        $urlPrint = 'byOperationDetailPrint';
        $title = '-> حسب العملية - اجمالي ' . $operation->name;

        return view('reports.movements.print.by_operation_summary', compact(
            'summary',
            'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
            'main_groups',
            'sub_groups',
        ));

    }

    public function byOperationSummaryBySubGroup($transactions,$id)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // -----------------------------------------
        // الكود الأصلي الخاص بتجميع الأصناف
        // -----------------------------------------
        $summary = $transactions->flatMap(function ($transaction) {
            return $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product?->item?->name ?? '-',
                    'main_group_id' => $item->product?->item?->main_group_id,
                    'sub_group_id'  => $item->product?->item?->sub_group_id,
                    'store_from'   => $transaction->FromStore?->id,
                    'store_to'     => $transaction->ToStore?->id,
                    'count'        => $item->count,
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
                $mainGroup = $items->first()['main_group_id'];
                $subGroup = $items->first()['sub_group_id'];

                return [
                    'product_name' => $productName,
                    'main_group_id' => $mainGroup,
                    'sub_group_id' => $subGroup,
                    'total_count' => $totalCount,
                    'store_count' => $storeCount,
                ];
            })
            ->values();

        // -----------------------------------------
        // ✔ إضافة التجميع حسب المجموعة الفرعية
        // -----------------------------------------
        $summaryBySubGroup = $summary
            ->groupBy('sub_group_id')
            ->map(function ($groupItems) {

                $subGroupId = $groupItems->first()['sub_group_id'];
                $subGroupName = SubGroup::find($subGroupId)?->name ?? '-';

                $items = $groupItems->map(function ($item) {
                    return [
                        'product_name' => $item['product_name'],
                        'total_count'  => $item['total_count'],
                        'store_count'  => $item['store_count'],
                    ];
                });

                $subTotal = $items->sum('total_count');

                return [
                    'sub_group_id'   => $subGroupId,
                    'sub_group_name' => $subGroupName,
                    'items'          => $items,
                    'sub_total'      => $subTotal,
                ];
            })
            ->values();

        // -----------------------------------------
        // إرجاع البيانات للعرض
        // -----------------------------------------
        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint = 'byOperationDetailPrint';
        $title = '-> حسب العملية - اجمالي ' . $operation->name;

        return view('reports.movements.by_operation_summary_by_sub_group', compact(
            'summary',
            'summaryBySubGroup', // ← الإضافة الجديدة
            'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
            'main_groups',
            'sub_groups',
        ));
    }

    public function byOperationSummaryBySubGroupPrint($transactions,$id)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // -----------------------------------------
        // الكود الأصلي الخاص بتجميع الأصناف
        // -----------------------------------------
        $summary = $transactions->flatMap(function ($transaction) {
            return $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product?->item?->name ?? '-',
                    'main_group_id' => $item->product?->item?->main_group_id,
                    'sub_group_id'  => $item->product?->item?->sub_group_id,
                    'store_from'   => $transaction->FromStore?->id,
                    'store_to'     => $transaction->ToStore?->id,
                    'count'        => $item->count,
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
                $mainGroup = $items->first()['main_group_id'];
                $subGroup = $items->first()['sub_group_id'];

                return [
                    'product_name' => $productName,
                    'main_group_id' => $mainGroup,
                    'sub_group_id' => $subGroup,
                    'total_count' => $totalCount,
                    'store_count' => $storeCount,
                ];
            })
            ->values();

        // -----------------------------------------
        // ✔ إضافة التجميع حسب المجموعة الفرعية
        // -----------------------------------------
        $summaryBySubGroup = $summary
            ->groupBy('sub_group_id')
            ->map(function ($groupItems) {

                $subGroupId = $groupItems->first()['sub_group_id'];
                $subGroupName = SubGroup::find($subGroupId)?->name ?? '-';

                $items = $groupItems->map(function ($item) {
                    return [
                        'product_name' => $item['product_name'],
                        'total_count'  => $item['total_count'],
                        'store_count'  => $item['store_count'],
                    ];
                });

                $subTotal = $items->sum('total_count');

                return [
                    'sub_group_id'   => $subGroupId,
                    'sub_group_name' => $subGroupName,
                    'items'          => $items,
                    'sub_total'      => $subTotal,
                ];
            })
            ->values();

        // -----------------------------------------
        // إرجاع البيانات للعرض
        // -----------------------------------------
        $operation = Movement::findOrFail($id ?? 2);
        $urlPrint = 'byOperationDetailPrint';
        $title = '-> حسب العملية - اجمالي ' . $operation->name;

        return view('reports.movements.print.by_operation_summary_by_sub_group', compact(
            'summary',
            'summaryBySubGroup', // ← الإضافة الجديدة
            'operation',
            'stores',
            'transactions',
            'users',
            'urlPrint',
            'employees',
            'products',
            'title',
            'id',
            'main_groups',
            'sub_groups',
        ));
    }
    public function byStoreDetail($id = 3)
    {


        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id') ? Store::where('id', request('store_id'))->get() : Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

// فلترة حسب نوع الحركة
        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }

// فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
            });
        }

// فلترة حسب المجموعات
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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

        $filterstores = Store::all();
        $transactions = $query->get();

        if (request('summary')) {
            if (request('summary')==1)
            return $this->byStoreSummary($transactions);
            else if (request('summary')==2)
            return $this->byStoreSummaryBySubGroup($transactions);

        }

        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byStoreDetail';
        $title = '-> حسب المستودع - تحليلي';
        $movements = Movement::all();

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
            'products',
            'main_groups',
            'sub_groups'
        ));

    }



    public function byStoreDetailPrint($id = 3)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id') ? Store::where('id', request('store_id'))->get() : Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

        // فلترة حسب نوع الحركة
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

        // فلترة حسب المجموعات
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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

        $filterstores = Store::all();
        $transactions = $query->get();

        if (request('summary')) {
            return $this->byStoreSummaryPrint($transactions);
        }

        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byStoreDetail';

        return view('reports.movements.print.by_store_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'url',
            'filterstores',
            'employees',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byStoreSummary($transactions, $id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')
            ? Store::where('id', request('store_id'))->get()
            : Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // تجميع الإجماليات حسب المخزن والصنف والوحدة
        $summary = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';
                $mainGroupId = $item->product?->item?->main_group_id;
                $subGroupId = $item->product?->item?->sub_group_id;

                // من مخزن (خارج)
                if ($transaction->FromStore) {
                    $storeName = $transaction->FromStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0,
                            'main_group_id' => $mainGroupId,
                            'sub_group_id' => $subGroupId,
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['out'] += $item->count;
                }

                // إلى مخزن (داخل)
                if ($transaction->ToStore) {
                    $storeName = $transaction->ToStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0,
                            'main_group_id' => $mainGroupId,
                            'sub_group_id' => $subGroupId,
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['in'] += $item->count;
                }
            }
        }

        $url = 'byStoreDetail';
        $title = '-> حسب المستودع - اجمالي';
        $filterstores = Store::all();
        $movements = Movement::all();

        return view('reports.movements.by_store_summary', compact(
            'stores',
            'summary',
            'transactions',
            'users',
            'filterstores',
            'url',
            'employees',
            'title',
            'movements',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byStoreSummaryPrint($transactions, $id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = request('store_id')
            ? Store::where('id', request('store_id'))->get()
            : Store::all();

        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // تجميع الإجماليات حسب المخزن والصنف والوحدة
        $summary = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';
                $mainGroupId = $item->product?->item?->main_group_id;
                $subGroupId = $item->product?->item?->sub_group_id;

                // من مخزن (خارج)
                if ($transaction->FromStore) {
                    $storeName = $transaction->FromStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0,
                            'main_group_id' => $mainGroupId,
                            'sub_group_id' => $subGroupId,
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['out'] += $item->count;
                }

                // إلى مخزن (داخل)
                if ($transaction->ToStore) {
                    $storeName = $transaction->ToStore->name;
                    if (!isset($summary[$storeName][$productName][$unitName])) {
                        $summary[$storeName][$productName][$unitName] = [
                            'in' => 0,
                            'out' => 0,
                            'main_group_id' => $mainGroupId,
                            'sub_group_id' => $subGroupId,
                        ];
                    }
                    $summary[$storeName][$productName][$unitName]['in'] += $item->count;
                }
            }
        }

        $url = 'byStoreDetail';
        $filterstores = Store::all();

        return view('reports.movements.print.by_store_summary', compact(
            'stores',
            'summary',
            'transactions',
            'users',
            'filterstores',
            'url',
            'employees',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byStoreSummaryBySubGroup($transactions)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $filterstores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
        $movements = Movement::all();

        $title = '-> حسب المستودع - اجمالي حسب المجموعات الفرعية';
        $url = 'byStoreDetail';
        $urlPrint = 'byStoreDetailPrint';

        $summary = [];

        foreach ($stores as $store) {

            // نجمع كل الحركات التي تخص هذا المستودع
            $storeTransactions = $transactions->filter(function ($t) use ($store) {
                return $t->FromStore?->id == $store->id || $t->ToStore?->id == $store->id;
            });

            if ($storeTransactions->isEmpty()) continue;

            $items = [];

            foreach ($storeTransactions as $transaction) {
                foreach ($transaction->items as $item) {

                    $product = $item->product?->item;
                    if (!$product) continue;

                    $productId = $product->id;
                    $productName = $product->name ?? 'بدون اسم';

                    $subGroupId = $product->sub_group_id;
                    $subGroupName = $product->subGroup?->name ?? 'بدون مجموعة';

                    if (!isset($items[$productId])) {
                        $items[$productId] = [
                            'product_name' => $productName,
                            'sub_group_id' => $subGroupId,
                            'sub_group_name' => $subGroupName,
                            'in'  => 0,
                            'out' => 0,
                            'net' => 0,
                        ];
                    }

                    // إذا كان المستودع هو المستقبل → دخول
                    if ($transaction->ToStore?->id == $store->id) {
                        $items[$productId]['in'] += $item->count;
                    }

                    // إذا كان المستودع هو المصدر → خروج
                    if ($transaction->FromStore?->id == $store->id) {
                        $items[$productId]['out'] += $item->count;
                    }

                    $items[$productId]['net'] =
                        $items[$productId]['in'] - $items[$productId]['out'];
                }
            }

            // Collection + Group By SubGroup
            $items = collect(array_values($items));

            $grouped = $items
                ->groupBy('sub_group_id')
                ->map(function ($group) {

                    $subGroupName = $group->first()['sub_group_name'];

                    $products = $group->map(function ($i) {
                        return [
                            'name'  => $i['product_name'],
                            'in'    => $i['in'],
                            'out'   => $i['out'],
                            'total' => $i['net'],
                        ];
                    });

                    return [
                        'group_name'  => $subGroupName,
                        'items'       => $products,
                        'total_in'    => $products->sum('in'),
                        'total_out'   => $products->sum('out'),
                        'group_total' => $products->sum('total'),
                    ];
                });

            // احفظ المستودع
            $summary[] = [
                'store_id'   => $store->id,
                'store_name' => $store->name,
                'groups'     => $grouped,
            ];
        }

        return view('reports.movements.by_store_summary_by_subgroup', compact(
            'summary',
            'stores',
            'filterstores',
            'transactions',
            'users',
            'employees',
            'url',
            'urlPrint',
            'title',
            'movements',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }


    public function byProductDetail($id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

        if ($id) {
            $query->where('movement_id', $id);
        }

        if (request('move_id')) {
            $query->where('movement_id', request('move_id'));
        }

        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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
            return $this->byProductSummary($transactions);
        }

        // ============= تجميع حسب الصنف + إضافة داخل/خارج/صافي =============

        $grouped = [];
        $filterProductId = request('product_id');
        $storeId = request('store_id'); // مهم لمعرفة الداخل والخارج

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {

                if ($filterProductId && $item->product_id != $filterProductId) {
                    continue;
                }

                $productName = $item->product?->item?->name ?? 'غير محدد';

                // تحديد داخلي/خارجي
                $isIn  = ($transaction->to_store == $storeId);
                $isOut = ($transaction->from_store == $storeId);

                $in  = $isIn  ? $item->count : 0;
                $out = $isOut ? $item->count : 0;
                $net = $in - $out;

                $grouped[$productName][] = [
                    'transaction' => $transaction,
                    'item'        => $item,
                    'in'          => $in,
                    'out'         => $out,
                    'net'         => $net,
                ];
            }
        }

        // ============= الإضافات الأساسية =============
        $operation = Movement::find($id);
        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - تحليلي';
        $movements = Movement::all();

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
            'grouped',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byProductDetailPrint($id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

        // فلترة حسب المجموعات
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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

        $transactions = $query->get();

        if (request('summary')) {
            return $this->byProductSummaryPrint($transactions);
        }

        $grouped = [];
        $filterProductId = request('product_id');

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
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
        $url = 'byProductDetail';
        $title = '-> حسب الصنف - تحليلي';

        return view('reports.movements.print.by_item_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products',
            'url',
            'title',
            'grouped',
            'main_groups',
            'sub_groups'
        ));
    }


    public function byProductSummary($transactions, $id = null)
    {

        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';
                $mainGroupId = $item->product?->item?->main_group_id;
                $subGroupId = $item->product?->item?->sub_group_id;

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = [
                        'count' => 0,
                        'main_group_id' => $mainGroupId,
                        'sub_group_id' => $subGroupId,
                    ];
                }

                $summary[$productName][$unitName]['count'] += $item->count;
            }
        }

        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - اجمالي';
        $movements = Movement::all();

        return view('reports.movements.by_item_summary', compact(
            'summary',
            'stores',
            'users',
            'employees',
            'products',
            'title',
            'urlPrint',
            'movements',
            'url',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byProductSummaryPrint($transactions, $id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';
                $mainGroupId = $item->product?->item?->main_group_id;
                $subGroupId = $item->product?->item?->sub_group_id;

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = [
                        'count' => 0,
                        'main_group_id' => $mainGroupId,
                        'sub_group_id' => $subGroupId,
                    ];
                }

                $summary[$productName][$unitName]['count'] += $item->count;
            }
        }

        $url = 'byProductDetail';
        $title = '-> حسب الصنف - اجمالي';

        return view('reports.movements.print.by_item_summary', compact(
            'summary',
            'stores',
            'users',
            'employees',
            'products',
            'title',
            'url',
            'main_groups',
            'sub_groups'
        ));
    }


    public function byEmployeeDetail($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

        // فلترة حسب نوع الحركة
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

        // فلترة حسب المجموعة الرئيسية
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        // فلترة حسب المجموعة الفرعية
        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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

        $transactions = $query->get();

        if (request('summary')) {
            if (request('summary')==1)
            return $this->byEmployeeSummary($transactions);
            if (request('summary')==2)
            return $this->byEmployeeSummaryBySubGroup($transactions);
        }

        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $title = '-> حسب المستخدم - تحليلي';
        $movements = Movement::all();

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
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byEmployeeDetailPrint($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

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

        // فلترة حسب نوع الحركة
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

        // فلترة حسب المجموعة الرئيسية
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        // فلترة حسب المجموعة الفرعية
        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
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

        $transactions = $query->get();

        if (request('summary')) {
            if (request('summary')==1)
            return $this->byEmployeeSummaryPrint($transactions);
            else if (request('summary')==2)
            return $this->byEmployeeSummaryBySubGroupPrint($transactions);
        }

        $operation = Movement::findOrFail($id ?: 2);
        $url = 'byEmployeeDetail';
        $title = '-> حسب المستخدم - تحليلي';

        return view('reports.movements.print.by_employee_detail', compact(
            'operation',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byEmployeeSummary($transactions)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
        $url = 'byEmployeeDetail';

        // نجمع حسب الموظف
        $grouped = $transactions->groupBy('employee_id');
        $summary = [];

        foreach ($grouped as $employeeId => $employeeTransactions) {
            $employeeName = $employeeTransactions->first()->employee?->item?->name ?? 'بدون اسم موظف';

            // كل الأصناف التي تعامل معها الموظف
            $allItems = $employeeTransactions->flatMap->items;

            // نجمع حسب الصنف
            $itemsGrouped = $allItems->groupBy(function ($item) {
                return $item->product?->item?->name ?? 'صنف غير معروف';
            });

            foreach ($itemsGrouped as $itemName => $itemsSet) {
                $mainGroupId = $itemsSet->first()->product?->item?->main_group_id;
                $subGroupId = $itemsSet->first()->product?->item?->sub_group_id;

                // العمليات الداخلة والخارجة
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
                    'main_group_id' => $mainGroupId,
                    'sub_group_id' => $subGroupId,
                    'total_in' => $totalIn,
                    'total_out' => $totalOut,
                    'transaction_count' => $transactionCount,
                ];
            }
        }

        $title = '-> حسب المستخدم - اجمالي';
        $urlPrint = 'byEmployeeDetailPrint';
        $movements = Movement::all();

        return view('reports.movements.by_employee_summary', compact(
            'summary',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'movements',
            'urlPrint',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }

    public function byEmployeeSummaryPrint($transactions)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
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
                $mainGroupId = $itemsSet->first()->product?->item?->main_group_id;
                $subGroupId = $itemsSet->first()->product?->item?->sub_group_id;

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
                    'main_group_id' => $mainGroupId,
                    'sub_group_id' => $subGroupId,
                    'total_in' => $totalIn,
                    'total_out' => $totalOut,
                    'transaction_count' => $transactionCount,
                ];
            }
        }

        $title = '-> حسب المستخدم - اجمالي';

        return view('reports.movements.print.by_employee_summary', compact(
            'summary',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }


    public function byEmployeeSummaryBySubGroup($transactions)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $movements = Movement::all();
        $title = '-> حسب المستخدم - اجمالي حسب المجموعات الفرعية';

        $summary = [];

        foreach ($employees as $employee) {
            $employeeTransactions = $transactions->filter(fn($t) => $t->employee_id == $employee->id);

            if ($employeeTransactions->isEmpty()) {
                continue;
            }

            // كل الأصناف التي تعامل معها الموظف
            // جمع كل الأصناف لكل موظف بحيث كل صنف يظهر مرة واحدة
            $items = [];

            foreach ($employeeTransactions as $transaction) {
                foreach ($transaction->items as $item) {
                    $product = $item->product?->item;
                    if (!$product) continue;

                    $productId = $product->id;
                    $productName = $product->name ?? 'بدون اسم';
                    $subGroupId = $product->sub_group_id;
                    $subGroupName = $product->subGroup?->name ?? 'بدون مجموعة';

                    // تهيئة إذا لم يكن موجود
                    if (!isset($items[$productId])) {
                        $items[$productId] = [
                            'product_name'   => $productName,
                            'sub_group_id'   => $subGroupId,
                            'sub_group_name' => $subGroupName,
                            'in'             => 0,
                            'out'            => 0,
                            'net'            => 0,
                        ];
                    }

                    // جمع الداخل والخارج حسب حركة الموفمنت
                    $items[$productId]['in'] += $transaction->movement?->direction == '1' ? $item->count : 0;
                    $items[$productId]['out'] += $transaction->movement?->direction == '0' ? $item->count : 0;
                    $items[$productId]['net'] = $items[$productId]['in'] - $items[$productId]['out'];
                }
            }

// تحويل إلى Collection للسهولة في التجميع حسب المجموعة الفرعية
            $items = collect(array_values($items));

            // تجميع حسب المجموعة الفرعية
            $grouped = $items
                ->groupBy('sub_group_id')
                ->map(function ($group) {
                    $subGroupName = $group->first()['sub_group_name'];

                    $products = $group->map(function ($i) {
                        return [
                            'name'  => $i['product_name'],
                            'in'    => $i['in'],
                            'out'   => $i['out'],
                            'total' => $i['net'],
                        ];
                    });

                    return [
                        'group_name' => $subGroupName,
                        'items'      => $products,
                        'total_in'   => $products->sum('in'),
                        'total_out'  => $products->sum('out'),
                        'group_total'=> $products->sum('total'),
                    ];
                });

            $summary[] = [
                'employee_id'   => $employee->id,
                'employee_name' => $employee->item?->name ?? 'موظف غير معروف',
                'groups'        => $grouped,
            ];
        }

        return view('reports.movements.by_employee_summary_by_subgroup', compact(
            'summary',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'movements',
            'urlPrint',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }
    public function byEmployeeSummaryBySubGroupPrint($transactions)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();
        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $movements = Movement::all();
        $title = '-> حسب المستخدم - اجمالي حسب المجموعات الفرعية';

        $summary = [];

        foreach ($employees as $employee) {
            $employeeTransactions = $transactions->filter(fn($t) => $t->employee_id == $employee->id);

            if ($employeeTransactions->isEmpty()) {
                continue;
            }

            // كل الأصناف التي تعامل معها الموظف
            // جمع كل الأصناف لكل موظف بحيث كل صنف يظهر مرة واحدة
            $items = [];

            foreach ($employeeTransactions as $transaction) {
                foreach ($transaction->items as $item) {
                    $product = $item->product?->item;
                    if (!$product) continue;

                    $productId = $product->id;
                    $productName = $product->name ?? 'بدون اسم';
                    $subGroupId = $product->sub_group_id;
                    $subGroupName = $product->subGroup?->name ?? 'بدون مجموعة';

                    // تهيئة إذا لم يكن موجود
                    if (!isset($items[$productId])) {
                        $items[$productId] = [
                            'product_name'   => $productName,
                            'sub_group_id'   => $subGroupId,
                            'sub_group_name' => $subGroupName,
                            'in'             => 0,
                            'out'            => 0,
                            'net'            => 0,
                        ];
                    }

                    // جمع الداخل والخارج حسب حركة الموفمنت
                    $items[$productId]['in'] += $transaction->movement?->direction == '1' ? $item->count : 0;
                    $items[$productId]['out'] += $transaction->movement?->direction == '0' ? $item->count : 0;
                    $items[$productId]['net'] = $items[$productId]['in'] - $items[$productId]['out'];
                }
            }

// تحويل إلى Collection للسهولة في التجميع حسب المجموعة الفرعية
            $items = collect(array_values($items));

            // تجميع حسب المجموعة الفرعية
            $grouped = $items
                ->groupBy('sub_group_id')
                ->map(function ($group) {
                    $subGroupName = $group->first()['sub_group_name'];

                    $products = $group->map(function ($i) {
                        return [
                            'name'  => $i['product_name'],
                            'in'    => $i['in'],
                            'out'   => $i['out'],
                            'total' => $i['net'],
                        ];
                    });

                    return [
                        'group_name' => $subGroupName,
                        'items'      => $products,
                        'total_in'   => $products->sum('in'),
                        'total_out'  => $products->sum('out'),
                        'group_total'=> $products->sum('total'),
                    ];
                });

            $summary[] = [
                'employee_id'   => $employee->id,
                'employee_name' => $employee->item?->name ?? 'موظف غير معروف',
                'groups'        => $grouped,
            ];
        }

        return view('reports.movements.print.by_employee_summary_by_subgroup', compact(
            'summary',
            'employees',
            'transactions',
            'users',
            'url',
            'stores',
            'title',
            'movements',
            'urlPrint',
            'products',
            'main_groups',
            'sub_groups'
        ));
    }



    public function bySubGroupDetail($id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ]);

        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المجموعات أو الصنف
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
            });
        }

        if (request('product_id')) {
            $query->whereHas('items', function ($q) {
                $q->where('product_id', request('product_id'));
            });
        }

        // فلترة المستخدم، الموظف، الحركة
        if (request('user_id')) $query->where('user_id', request('user_id'));
        if (request('employee_id')) $query->where('employee_id', request('employee_id'));
        if ($id) $query->where('movement_id', $id);
        if (request('move_id')) $query->where('movement_id', request('move_id'));

        // فلترة التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $transactions = $query->get();

        // تصفية داخلية للعناصر ضمن العمليات نفسها
        $transactions->each(function ($transaction) {
            $transaction->setRelation('items', $transaction->items->filter(function ($item) {
                $ok = true;

                if (request('main_group_id')) {
                    $ok = $ok && $item->product?->item?->main_group_id == request('main_group_id');
                }

                if (request('sub_group_id')) {
                    $ok = $ok && $item->product?->item?->sub_group_id == request('sub_group_id');
                }

                if (request('product_id')) {
                    $ok = $ok && $item->product_id == request('product_id');
                }

                return $ok;
            }));
        });

        // إزالة العمليات التي أصبحت بلا عناصر بعد الفلترة
        $transactions = $transactions->filter(fn($t) => $t->items->isNotEmpty());

        if (request('summary')) {
            return $this->bySubGroupSummary($transactions);
        }

        // تجميع العمليات حسب المجموعة الفرعية
        $grouped = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $subGroup = $item->product?->item?->sub_group?->name ?? 'غير محدد';
                $grouped[$subGroup][] = [
                    'transaction' => $transaction,
                    'item' => $item,
                ];
            }
        }

        $operation = Movement::find($id);
        $url = 'bySubGroupDetail';
        $urlPrint = 'bySubGroupDetailPrint';
        $title = '-> حسب المجموعة الفرعية - تحليلي';
        $movements = Movement::all();

        return view('reports.movements.by_sub_group_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products',
            'main_groups',
            'sub_groups',
            'url',
            'urlPrint',
            'title',
            'movements',
            'grouped'
        ));
    }

    public function bySubGroupDetailPrint($id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $stores = Store::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', function ($q) {
            $q->where('department_id', 1);
        })->get();

        $query = StoreTransaction::with([
            'user',
            'employee',
            'FromStore',
            'ToStore',
            'items.product.item',
            'items.unit.unit'
        ]);

        if (!request('from_date') && !request('to_date')) {
            request()->merge(['from_date' => now()->toDateString()]);
        }

        // فلترة حسب المجموعات أو الصنف
        if (request('main_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('main_group_id', request('main_group_id'));
            });
        }

        if (request('sub_group_id')) {
            $query->whereHas('items.product.item', function ($q) {
                $q->where('sub_group_id', request('sub_group_id'));
            });
        }

        if (request('product_id')) {
            $query->whereHas('items', function ($q) {
                $q->where('product_id', request('product_id'));
            });
        }

        // فلترة المستخدم، الموظف، الحركة
        if (request('user_id')) $query->where('user_id', request('user_id'));
        if (request('employee_id')) $query->where('employee_id', request('employee_id'));
        if ($id) $query->where('movement_id', $id);
        if (request('move_id')) $query->where('movement_id', request('move_id'));

        // فلترة التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $transactions = $query->get();

        // تصفية داخلية للعناصر ضمن العمليات نفسها
        $transactions->each(function ($transaction) {
            $transaction->setRelation('items', $transaction->items->filter(function ($item) {
                $ok = true;

                if (request('main_group_id')) {
                    $ok = $ok && $item->product?->item?->main_group_id == request('main_group_id');
                }

                if (request('sub_group_id')) {
                    $ok = $ok && $item->product?->item?->sub_group_id == request('sub_group_id');
                }

                if (request('product_id')) {
                    $ok = $ok && $item->product_id == request('product_id');
                }

                return $ok;
            }));
        });

        // إزالة العمليات التي أصبحت بلا عناصر بعد الفلترة
        $transactions = $transactions->filter(fn($t) => $t->items->isNotEmpty());

        if (request('summary')) {
            return $this->bySubGroupSummaryPrint($transactions);
        }

        // تجميع العمليات حسب المجموعة الفرعية
        $grouped = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $subGroup = $item->product?->item?->sub_group?->name ?? 'غير محدد';
                $grouped[$subGroup][] = [
                    'transaction' => $transaction,
                    'item' => $item,
                ];
            }
        }

        $operation = Movement::find($id);
        $url = 'bySubGroupDetail';
        $urlPrint = 'bySubGroupDetailPrint';
        $title = '-> حسب المجموعة الفرعية - تحليلي';
        $movements = Movement::all();

        return view('reports.movements.print.by_sub_group_detail', compact(
            'operation',
            'stores',
            'transactions',
            'users',
            'employees',
            'products',
            'main_groups',
            'sub_groups',
            'url',
            'urlPrint',
            'title',
            'movements',
            'grouped'
        ));
    }
    public function bySubGroupSummary($transactions)
    {
        $summary = [];

        foreach ($transactions as $transaction) {
            // تحديد نوع الحركة
            $isIn = !empty($transaction->to_store_id) && empty($transaction->from_store_id);

            foreach ($transaction->items as $item) {
                $main = $item->product?->item?->mainGroup?->name ?? 'غير محدد';
                $sub  = $item->product?->item?->subGroup?->name ?? 'غير محدد';
                $qty  = $item->count ?? 0;

                if (!isset($summary[$main])) {
                    $summary[$main] = [
                        'subGroups' => [],
                        'total' => 0,

                    ];
                }

                if (!isset($summary[$main]['subGroups'][$sub])) {
                    $summary[$main]['subGroups'][$sub] = [
                        'total' => 0,

                    ];
                }


                    $summary[$main]['subGroups'][$sub]['total'] += $qty;
                    $summary[$main]['total'] += $qty;

            }
        }

        $operation = Movement::find(request('move_id'));
        $url = 'bySubGroupDetail';
        $urlPrint = 'bySubGroupDetailPrint';
        $title = '-> حسب المجموعة الفرعية - إجمالي';
        $stores = Store::all();
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', fn($q) => $q->where('department_id', 1))->get();
        $movements = Movement::all();

        return view('reports.movements.by_sub_group_summary', compact(
            'summary',
            'operation',
            'stores',
            'users',
            'employees',
            'products',
            'main_groups',
            'sub_groups',
            'url',
            'urlPrint',
            'title',
            'movements'
        ));
    }




    public function bySubGroupSummaryPrint($transactions)
    {
        $summary = [];

        foreach ($transactions as $transaction) {
            // تحديد نوع الحركة
            $isIn = !empty($transaction->to_store_id) && empty($transaction->from_store_id);

            foreach ($transaction->items as $item) {
                $main = $item->product?->item?->mainGroup?->name ?? 'غير محدد';
                $sub  = $item->product?->item?->subGroup?->name ?? 'غير محدد';
                $qty  = $item->count ?? 0;

                if (!isset($summary[$main])) {
                    $summary[$main] = [
                        'subGroups' => [],
                        'total' => 0,

                    ];
                }

                if (!isset($summary[$main]['subGroups'][$sub])) {
                    $summary[$main]['subGroups'][$sub] = [
                        'total' => 0,

                    ];
                }


                $summary[$main]['subGroups'][$sub]['total'] += $qty;
                $summary[$main]['total'] += $qty;

            }
        }

        $operation = Movement::find(request('move_id'));
        $url = 'bySubGroupDetail';
        $urlPrint = 'bySubGroupDetailPrint';
        $title = '-> حسب المجموعة الفرعية - إجمالي';
        $stores = Store::all();
        $users = User::all();
        $employees = Employee::all();
        $products = Product::all();
        $main_groups = MainGroup::where('department_id', 1)->get();
        $sub_groups = SubGroup::whereHas('mainGroup', fn($q) => $q->where('department_id', 1))->get();
        $movements = Movement::all();

        return view('reports.movements.print.by_sub_group_summary', compact(
            'summary',
            'operation',
            'stores',
            'users',
            'employees',
            'products',
            'main_groups',
            'sub_groups',
            'url',
            'urlPrint',
            'title',
            'movements'
        ));
    }



}
