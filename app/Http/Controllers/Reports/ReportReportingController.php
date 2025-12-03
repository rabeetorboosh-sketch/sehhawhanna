<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Department;
use App\Models\Item;
use App\Models\Employee;
use App\Models\ControlUnit;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class ReportReportingController extends Controller
{
    public function index(Request $request)
    {
        $reports = $this->buildQuery($request)->get();

        // القوائم الخاصة بالفلاتر
        $departments  = Department::all();
        $items        = Item::all();
        $employees    = Employee::with('item')->get();
        $controlUnits = ControlUnit::all();
        $mainGroups   = MainGroup::with('department')->get();
        $subGroups    = SubGroup::all();

        // تحديد صفحة العرض (تفصيلي أو إجمالي)
        $view = $request->summary == 1
            ? 'reports.alerts.by_summary'
            : 'reports.alerts.by_detail';

        return view($view, compact(
            'reports',
            'departments',
            'items',
            'employees',
            'controlUnits',
            'mainGroups',
            'subGroups'
        ));
    }

    public function print(Request $request)
    {
        $reports = $this->buildQuery($request)->get();

        $view = $request->summary == 1
            ? 'reports.reporting.printing.by_summary'
            : 'reports.reporting.printing.by_detail';

        return view($view, compact('reports'));
    }

    private function buildQuery(Request $request)
    {
        $query = Report::query()
            ->with([
                'user',
                'reportType',
                'department',
                'branch',
                'items',
                'items.controlUnit',
                'items.causer',
                'items.item'
            ]);

        // فلترة التاريخ
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // فلترة حسب القسم
        if ($request->filled('section_id')) {
            $query->where('department_id', $request->section_id);
        }

        // فلترة حسب نوع البلاغ
        if ($request->filled('issue_type_id')) {
            $query->where('issue_type_id', $request->issue_type_id);
        }

        // فلترة حسب وحدة التحكم داخل ReportItem
        if ($request->filled('control_unit_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('control_unit_id', $request->control_unit_id);
            });
        }

        // فلترة حسب العنصر item_no
        if ($request->filled('item_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('item_no', $request->item_id);
            });
        }

        // فلترة المتسبب
        if ($request->filled('causer_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('causer_id', $request->causer_id);
            });
        }

        // فلترة حسب مشاكل فقط
        if ($request->issues == 1) {
            $query->whereHas('items', function ($q) {
                $q->whereNotNull('issue_description')
                    ->where('issue_description', '!=', '');
            });
        }

        // فلترة حسب المجموعات
        if ($request->filled('mainGroup')) {
            $query->whereHas('items.controlUnit', function ($q) use ($request) {
                $q->where('main_group_id', $request->mainGroup);
            });
        }

        if ($request->filled('subGroup')) {
            $query->whereHas('items.controlUnit', function ($q) use ($request) {
                $q->where('sub_group_id', $request->subGroup);
            });
        }

        return $query;
    }
}
