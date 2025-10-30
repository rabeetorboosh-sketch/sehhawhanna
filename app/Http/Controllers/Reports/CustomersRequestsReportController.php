<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerRequest;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersRequestsReportController extends Controller
{
public function index()
{
    return view('reports.customerRequest.index');

}
    public function byOperationDetail($id = null)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
            'items.product.item',
            'items.unit.unit',
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
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

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        } elseif (request('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        $requests = $query->get();

        $url = 'byOperationDetail';
        $urlPrint = 'byOperationDetailPrint';
        $title = 'طلبات العملاء -> حسب العملية - تفصيلي';

        return view('reports.customerRequest.by_operation_detail', compact(
            'requests',
            'users',
            'employees',
            'products',
            'url',
            'urlPrint',
            'title',
            'id'
        ));
    }

    public function byOperationDetailPrint($id =null)
    {

        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
            'items.product.item',
            'items.unit.unit',
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
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

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        } elseif (request('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        $requests = $query->get();

        $url = 'byOperationDetail';
        $urlPrint = 'byOperationDetailPrint';
        $title = 'طلبات العملاء -> حسب العملية - تفصيلي';

        return view('reports.customerRequest.print.by_operation_detail', compact(
            'requests',
            'users',
            'employees',
            'products',
            'url',
            'urlPrint',
            'title',
            'id'
        ));
    }

    public function byProductDetail($id = null)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
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

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $requests = $query->get();

        if (request('summary')) {
            return $this->byProductSummary($requests);
        }

        $grouped = [];
        $filterProductId = request('product_id');

        foreach ($requests as $request) {
            foreach ($request->items as $item) {

                if ($filterProductId && $item->product_id != $filterProductId) {
                    continue;
                }

                $productName = $item->product?->item?->name ?? 'غير محدد';
                $grouped[$productName][] = [
                    'request' => $request,
                    'item' => $item,
                ];
            }
        }

        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - تحليلي';

        return view('reports.customerRequest.by_item_detail', compact(
            'requests',
            'users',
            'employees',
            'products',
            'url',
            'urlPrint',
            'title',
            'grouped'
        ));
    }

    public function byProductDetailPrint( $id = null)
    {

        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
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

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $requests = $query->get();

        if (request('summary')) {
            return $this->byProductSummaryPrint($requests);
        }

        $grouped = [];
        $filterProductId = request('product_id');

        foreach ($requests as $request) {
            foreach ($request->items as $item) {

                if ($filterProductId && $item->product_id != $filterProductId) {
                    continue;
                }

                $productName = $item->product?->item?->name ?? 'غير محدد';
                $grouped[$productName][] = [
                    'request' => $request,
                    'item' => $item,
                ];
            }
        }

        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - تحليلي';

        return view('reports.customerRequest.print.by_item_detail', compact(
            'requests',
            'users',
            'employees',
            'products',
            'url',
            'urlPrint',
            'title',
            'grouped'
        ));
    }

    public function byProductSummary($requests, $id = null)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($requests as $request) {
            foreach ($request->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = 0;
                }

                $summary[$productName][$unitName] += $item->count;
            }
        }

        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - إجمالي';

        return view('reports.customerRequest.by_item_summary', compact(
            'summary',
            'users',
            'employees',
            'products',
            'title',
            'urlPrint',
            'url'
        ));
    }

    public function byProductSummaryPrint($requests,$id = null)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();

        // حساب الإجماليات حسب الصنف والوحدة
        $summary = [];

        foreach ($requests as $request) {
            foreach ($request->items as $item) {
                $productName = $item->product?->item?->name ?? 'غير محدد';
                $unitName = $item->unit?->unit?->name ?? '-';

                if (!isset($summary[$productName][$unitName])) {
                    $summary[$productName][$unitName] = 0;
                }

                $summary[$productName][$unitName] += $item->count;
            }
        }

        $url = 'byProductDetail';
        $urlPrint = 'byProductDetailPrint';
        $title = '-> حسب الصنف - إجمالي';

        return view('reports.customerRequest.print.by_item_summary', compact(
            'summary',
            'users',
            'employees',
            'products',
            'title',
            'urlPrint',
            'url'
        ));
    }


    public function byEmployeeDetail($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $query =CustomerRequest::with([
            'user',
            'employee',
            'salesRout',
            'items.product.item',
            'items.unit.unit'
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
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
        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $requests = $query->get();

        if (request('summary')) {
            return $this->byEmployeeSummary($requests);
        }

        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $title='-> حسب المستخدم - تحليلي';
        return view('reports.customerRequest.by_employee_detail', compact(
            'employees',
            'requests',
            'users',
            'url',
            'title',
            'urlPrint',
            'products'
        ));
    }
    public function byEmployeeDetailPrint($id = 3)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $query =CustomerRequest::with([
            'user',
            'employee',
            'salesRout',
            'items.product.item',
            'items.unit.unit'
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
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
        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $requests = $query->get();

        if (request('summary')) {
            return $this->byEmployeeSummaryPrint($requests);
        }

        $url = 'byEmployeeDetail';
        $urlPrint = 'byEmployeeDetailPrint';
        $title='-> حسب المستخدم - تحليلي';
        return view('reports.customerRequest.print.by_employee_detail', compact(
            'employees',
            'requests',
            'users',
            'url',
            'title',
            'urlPrint',
            'products'
        ));
    }


    public function byEmployeeSummary($requests)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $url = 'byEmployeeDetail';

        // تجميع الطلبات حسب الموظف
        $grouped = $requests->groupBy('employee_id');
        $summary = [];

        foreach ($grouped as $employeeId => $employeeRequests) {
            $employeeName = $employeeRequests->first()->employee?->item?->name ?? 'بدون اسم موظف';

            // جميع العناصر (items) التي تخص هذا الموظف
            $allItems = $employeeRequests->flatMap->items;

            // تجميع العناصر حسب اسم الصنف
            $itemsGrouped = $allItems->groupBy(function ($item) {
                return $item->product?->item?->name ?? 'صنف غير معروف';
            });

            foreach ($itemsGrouped as $itemName => $itemsSet) {
                $totalCount = $itemsSet->sum('count');

                // عدد الطلبات التي تحتوي هذا الصنف
                $requestCount = $employeeRequests->filter(function ($r) use ($itemName) {
                    return $r->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
                })->count();

                $summary[] = [
                    'employee_name'   => $employeeName,
                    'item_name'       => $itemName,
                    'total_count'     => $totalCount,
                    'request_count'   => $requestCount,
                ];
            }
        }

        $title = '-> حسب الموظف - إجمالي';
        $urlPrint = 'byEmployeeDetailPrint';

        return view('reports.customerRequest.by_employee_summary', compact(
            'summary',
            'employees',
            'requests',
            'users',
            'url',
            'title',
            'urlPrint',
            'products'
        ));
    }

    public function byEmployeeSummaryPrint($requests)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $products = Product::all();
        $url = 'byEmployeeDetail';

        // تجميع الطلبات حسب الموظف
        $grouped = $requests->groupBy('employee_id');
        $summary = [];

        foreach ($grouped as $employeeId => $employeeRequests) {
            $employeeName = $employeeRequests->first()->employee?->item?->name ?? 'بدون اسم موظف';

            // جميع العناصر (items) التي تخص هذا الموظف
            $allItems = $employeeRequests->flatMap->items;

            // تجميع العناصر حسب اسم الصنف
            $itemsGrouped = $allItems->groupBy(function ($item) {
                return $item->product?->item?->name ?? 'صنف غير معروف';
            });

            foreach ($itemsGrouped as $itemName => $itemsSet) {
                $totalCount = $itemsSet->sum('count');

                // عدد الطلبات التي تحتوي هذا الصنف
                $requestCount = $employeeRequests->filter(function ($r) use ($itemName) {
                    return $r->items->contains(function ($i) use ($itemName) {
                        return ($i->product?->item?->name ?? '') === $itemName;
                    });
                })->count();

                $summary[] = [
                    'employee_name'   => $employeeName,
                    'item_name'       => $itemName,
                    'total_count'     => $totalCount,
                    'request_count'   => $requestCount,
                ];
            }
        }

        $title = '-> حسب الموظف - إجمالي';
        $urlPrint = 'byEmployeeDetailPrint';

        return view('reports.customerRequest.print.by_employee_summary', compact(
            'summary',
            'employees',
            'requests',
            'users',
            'url',
            'title',
            'urlPrint',
            'products'
        ));
    }

    public function byCustomerDetail()
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $customers = Customer::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
            'items.product.item',
            'items.unit.unit',
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        // فلترة حسب العميل
        if (request('customer_id')) {
            $query->where('customer_id', request('customer_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
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

        $requests = $query->get();

        if (request('summary')) {
            return $this->byCustomerSummary($requests);
        }

        $url = 'byCustomerDetail';
        $urlPrint = 'byCustomerDetailPrint';
        $title = '-> حسب العميل - تفصيلي';

        return view('reports.customerRequest.by_customer_detail', compact(
            'requests',
            'customers',
            'employees',
            'users',
            'products',
            'url',
            'urlPrint',
            'title'
        ));
    }
    public function byCustomerDetailPrint()
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $customers = Customer::with('item')->get();
        $products = Product::all();

        $query = CustomerRequest::with([
            'user',
            'employee.item',
            'customer.item',
            'salesRout',
            'items.product.item',
            'items.unit.unit',
        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }

        // فلترة حسب العميل
        if (request('customer_id')) {
            $query->where('customer_id', request('customer_id'));
        }

        // فلترة حسب الصنف
        if (request('product_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('id', request('product_id'));
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

        $requests = $query->get();

        if (request('summary')) {
            return $this->byCustomerSummaryPrint($requests);
        }

        $url = 'byCustomerDetail';
        $urlPrint = 'byCustomerDetailPrint';
        $title = '-> حسب العميل - تفصيلي';

        return view('reports.customerRequest.print.by_customer_detail', compact(
            'requests',
            'customers',
            'employees',
            'users',
            'products',
            'url',
            'urlPrint',
            'title'
        ));
    }
    public function byCustomerSummary($requests)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $customers = Customer::with('item')->get();
        $products = Product::all();

        $summary = [];

        foreach ($requests->groupBy('customer_id') as $customerId => $customerRequests) {
            $customerName = $customerRequests->first()->customer?->item?->name ?? 'عميل غير محدد';

            $allItems = $customerRequests->flatMap->items;

            $itemsGrouped = $allItems->groupBy(function ($item) {
                return $item->product?->item?->name ?? 'صنف غير معروف';
            });

            foreach ($itemsGrouped as $itemName => $itemsSet) {
                $totalCount = $itemsSet->sum('count');
                $summary[] = [
                    'customer_name' => $customerName,
                    'item_name'     => $itemName,
                    'total_count'   => $totalCount,
                    'requests_count'=> $customerRequests->count(),
                ];
            }
        }

        $url = 'byCustomerDetail';
        $urlPrint = 'byCustomerDetailPrint';
        $title = '-> حسب العميل - إجمالي';

        return view('reports.customerRequest.by_customer_summary', compact(
            'summary',
            'users',
            'employees',
            'customers',
            'products',
            'url',
            'urlPrint',
            'title'
        ));
    }
    public function byCustomerSummaryPrint($requests)
    {
        $users = User::all();
        $employees = Employee::with('item')->get();
        $customers = Customer::with('item')->get();
        $products = Product::all();

        $summary = [];

        foreach ($requests->groupBy('customer_id') as $customerId => $customerRequests) {
            $customerName = $customerRequests->first()->customer?->item?->name ?? 'عميل غير محدد';

            $allItems = $customerRequests->flatMap->items;

            $itemsGrouped = $allItems->groupBy(function ($item) {
                return $item->product?->item?->name ?? 'صنف غير معروف';
            });

            foreach ($itemsGrouped as $itemName => $itemsSet) {
                $totalCount = $itemsSet->sum('count');
                $summary[] = [
                    'customer_name' => $customerName,
                    'item_name'     => $itemName,
                    'total_count'   => $totalCount,
                    'requests_count'=> $customerRequests->count(),
                ];
            }
        }

        $url = 'byCustomerDetail';
        $urlPrint = 'byCustomerDetailPrint';
        $title = '-> حسب العميل - إجمالي';

        return view('reports.customerRequest.print.by_customer_summary', compact(
            'summary',
            'users',
            'employees',
            'customers',
            'products',
            'url',
            'urlPrint',
            'title'
        ));
    }

}
