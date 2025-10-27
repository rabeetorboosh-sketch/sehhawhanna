<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ControlUnit;
use App\Models\DailyControl;
use App\Models\Department;
use App\Models\Employee;
use App\Models\IssueType;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class MonitoringReportController extends Controller
{
   public  function index(Request $request)
   {

       $departments  = Department::all();
       $controlUnits = ControlUnit::all();
       $mainGroups   = MainGroup::with('department')->get();
       $subGroups    = SubGroup::all();
       $items        = Item::with('mainGroup','subGroup')->get();
       $issueTypes = IssueType::all();
       $employees=Employee::with('item')->get();
       $dailyControls = DailyControl::query();

// فلترة من تاريخ
       if ($request->filled('from_date')) {
           $dailyControls->whereDate('created_at', '>=', $request->from_date);
       }

// فلترة إلى تاريخ
       if ($request->filled('to_date')) {
           $dailyControls->whereDate('created_at', '<=', $request->to_date);
       }

// فلترة DailyControls حسب وجود Items تحقق الشروط
       $dailyControls->whereHas('items', function ($q) use ($request) {

           if ($request->filled('item_id')) {
               $q->where('item_id', $request->item_id);
           }

           if ($request->filled('section_id')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('department_id', $request->section_id);
               });
           }

           if ($request->filled('mainGroup')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('main_group_id', $request->mainGroup);
               });
           }

           if ($request->filled('subGroup')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('sub_group_id', $request->subGroup);
               });
           }

           if ($request->filled('control_unit_id')) {
               $q->where('control_unit_id', $request->control_unit_id);
           }

           if ($request->filled('causer_id')) {
               $q->where('causer_id', $request->causer_id);
           }

           if ($request->has('issues') && $request->issues == 1) {
               $q->where('is_correct', 0);
           }

       });

// جلب الـ DailyControls مع الـ items المصفاة فقط
       $dailyControls = $dailyControls->with(['items' => function ($q) use ($request) {

           if ($request->filled('item_id')) {
               $q->where('item_id', $request->item_id);
           }

           if ($request->filled('section_id')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('department_id', $request->section_id);
               });
           }

           if ($request->filled('mainGroup')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('main_group_id', $request->mainGroup);
               });
           }

           if ($request->filled('subGroup')) {
               $q->whereHas('controlUnit', function ($q2) use ($request) {
                   $q2->where('sub_group_id', $request->subGroup);
               });
           }

           if ($request->filled('control_unit_id')) {
               $q->where('control_unit_id', $request->control_unit_id);
           }

           if ($request->filled('causer_id')) {
               $q->where('causer_id', $request->causer_id);
           }

           if ($request->has('issues') && $request->issues == 1) {
               $q->where('is_correct', 0);
           }

       }])->get();

if($request->input('summary')==0){


    return view('reports.monitorings.by_detail',compact(

        'issueTypes',
        'departments',
        'controlUnits',
        'employees',
        'mainGroups',
        'subGroups',
        'items',
        'dailyControls'
    ));
}
else{


    return view('reports.monitorings.by_summary',compact(

        'issueTypes',
        'departments',
        'controlUnits',
        'employees',
        'mainGroups',
        'subGroups',
        'items',
        'dailyControls'
    ));
}

   }


    public  function print(Request $request)
    {

        $departments  = Department::all();
        $controlUnits = ControlUnit::all();
        $mainGroups   = MainGroup::with('department')->get();
        $subGroups    = SubGroup::all();
        $items        = Item::with('mainGroup','subGroup')->get();
        $issueTypes = IssueType::all();
        $employees=Employee::with('item')->get();
        $dailyControls = DailyControl::query();

// فلترة من تاريخ
        if ($request->filled('from_date')) {
            $dailyControls->whereDate('created_at', '>=', $request->from_date);
        }

// فلترة إلى تاريخ
        if ($request->filled('to_date')) {
            $dailyControls->whereDate('created_at', '<=', $request->to_date);
        }

// فلترة DailyControls حسب وجود Items تحقق الشروط
        $dailyControls->whereHas('items', function ($q) use ($request) {

            if ($request->filled('item_id')) {
                $q->where('item_id', $request->item_id);
            }

            if ($request->filled('section_id')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('department_id', $request->section_id);
                });
            }

            if ($request->filled('mainGroup')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('main_group_id', $request->mainGroup);
                });
            }

            if ($request->filled('subGroup')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('sub_group_id', $request->subGroup);
                });
            }

            if ($request->filled('control_unit_id')) {
                $q->where('control_unit_id', $request->control_unit_id);
            }

            if ($request->filled('causer_id')) {
                $q->where('causer_id', $request->causer_id);
            }

            if ($request->has('issues') && $request->issues == 1) {
                $q->where('is_correct', 0);
            }

        });

// جلب الـ DailyControls مع الـ items المصفاة فقط
        $dailyControls = $dailyControls->with(['items' => function ($q) use ($request) {

            if ($request->filled('item_id')) {
                $q->where('item_id', $request->item_id);
            }

            if ($request->filled('section_id')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('department_id', $request->section_id);
                });
            }

            if ($request->filled('mainGroup')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('main_group_id', $request->mainGroup);
                });
            }

            if ($request->filled('subGroup')) {
                $q->whereHas('controlUnit', function ($q2) use ($request) {
                    $q2->where('sub_group_id', $request->subGroup);
                });
            }

            if ($request->filled('control_unit_id')) {
                $q->where('control_unit_id', $request->control_unit_id);
            }

            if ($request->filled('causer_id')) {
                $q->where('causer_id', $request->causer_id);
            }

            if ($request->has('issues') && $request->issues == 1) {
                $q->where('is_correct', 0);
            }

        }])->get();

if ($request->filled('summary')==0)
        return view('reports.monitorings.printing.by_detail',compact(

            'issueTypes',
            'departments',
            'controlUnits',
            'employees',
            'mainGroups',
            'subGroups',
            'items',
            'dailyControls'
        ));
else
    return view('reports.monitorings.printing.by_summary',compact(

        'issueTypes',
        'departments',
        'controlUnits',
        'employees',
        'mainGroups',
        'subGroups',
        'items',
        'dailyControls'
    ));

    }
}
