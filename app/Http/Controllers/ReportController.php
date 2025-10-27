<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Customer;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\Media;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Branch;
use App\Models\IssueType;
use App\Models\Department;
use App\Models\ControlUnit;
use App\Models\Employee;
use App\Models\SubGroup;
use App\Models\Supplier;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index($department="")
    {
        if (Auth::user()->isAdmin())
            $reports = Report::with(['user', 'reportType', 'department', 'items'])->where('department_id',$department)->paginate(30);
        else
         $reports = Report::with(['user', 'reportType', 'department', 'items'])->where('department_id',$department)->where('user_id',Auth::user()->id)->paginate(30);

        return view('alerts.index', compact('reports','department'));
    }

    public function create($department="")
    {

        if (empty($department)) {
            // بدون فلترة
            $departments  = Department::all();
            $controlUnits = ControlUnit::all();
            $mainGroups   = MainGroup::with('department')->get();
            $subGroups    = SubGroup::all();
            $items        = Item::with('mainGroup','subGroup')->get();
        } else {
            // فلترة حسب رقم القسم
            $departments  = Department::where('id', $department)->get();

            $controlUnits = ControlUnit::where('department_id', $department)->get();

            $mainGroups   = MainGroup::with('department')
                ->where('department_id', $department)
                ->get();

            $subGroups    = SubGroup::whereHas('mainGroup', function ($q) use ($department) {
                $q->where('department_id', $department);
            })->get();

            $items        = Item::whereHas('mainGroup', function ($q) use ($department) {
                $q->where('department_id', $department);
            })
                ->with('mainGroup','subGroup')
                ->get();
        }
        $employees = Employee::all();
        $issueTypes = IssueType::all();
        return view('alerts.create', compact(
               'issueTypes',
             'departments',
                        'controlUnits',
                        'employees',
                        'mainGroups',
                        'subGroups',
                        'items',
        ));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $report = Report::create([
                'user_id'       => auth()->id(),
                'issue_type_id' => $request->report_type_id,
                'status'        => $request->status,
                'department_id' => $request->section_id,
                'branch_id'     => 1,
            ]);

            if ($request->items) {
                foreach ($request->items as $index => $item) {
                    [$itemNo, $itemType] = explode('-', $item['item_no']);
                    $reportItem = ReportItem::create([
                        'report_id'         => $report->id,
                        'item_no'           =>  $itemNo,
                        'item_type'           => $itemType ,
                        'control_unit_id'   => $item['control_unit_id'],
                        'user_control_unit' => $item['user_control_unit'],
                        'causer_id'         => $item['causer_id'] ?? null,
                        'issue_description' => $item['issue_description'],
                        'response_status'   => $item['response_status'] ?? null,
                        'branch_id'         => 1,
                    ]);

                    // صورة واحدة
                    if ($request->hasFile("items.$index.control_unit_photo")) {
                        $photo = $request->file("items.$index.control_unit_photo");
                        $path = $photo->store('uploads/report_items', 'public');

                        Media::create([
                            'item_id' => $reportItem->id,
                            'url'     => $path,
                            'type'    => 'ReportItem',
                        ]);
                    }

                    // صور متعددة
                    if ($request->hasFile("items.$index.control_unit_photos")) {

                        foreach ($request->file("items.$index.control_unit_photos") as $multiPhoto) {
                            $path = $multiPhoto->store('uploads/report_items', 'public');

                            Media::create([
                                'item_id' => $reportItem->id,
                                'url'     => $path,
                                'type'    => 'ReportItem',
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('reports.index',$request->section_id)->with('success', 'تم إنشاء التقرير بنجاح ✅');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء التقرير ⚠️: ' . $e->getMessage());
        }
    }

    public function edit($id,$department)
    {
        $report = Report::with(['items.media'])->findOrFail($id);

        if (empty($department)) {
            // بدون فلترة
            $departments  = Department::all();
            $controlUnits = ControlUnit::all();
            $mainGroups   = MainGroup::with('department')->get();
            $subGroups    = SubGroup::all();
            $items        = Item::with('mainGroup','subGroup')->get();
        } else {
            // فلترة حسب رقم القسم
            $departments  = Department::where('id', $department)->get();

            $controlUnits = ControlUnit::where('department_id', $department)->get();

            $mainGroups   = MainGroup::with('department')
                ->where('department_id', $department)
                ->get();

            $subGroups    = SubGroup::whereHas('mainGroup', function ($q) use ($department) {
                $q->where('department_id', $department);
            })->get();

            $items        = Item::whereHas('mainGroup', function ($q) use ($department) {
                $q->where('department_id', $department);
            })
                ->with('mainGroup','subGroup')
                ->get();
        }


        return view('alerts.edit', [
            'report'       => $report,
            'issueTypes'   => IssueType::all(),
            'departments'  => $departments,
            'controlUnits' => $controlUnits,
            'employees'    => Employee::all(),
            'mainGroups'   => $mainGroups,
            'subGroups'    => $subGroups,
            'assets'       => Asset::all(),
            'items'        => $items,
            'suppliers'    => Supplier::all(),
            'tasks'        => Task::all(),
            'customers'    => Customer::all(),
        ]);
    }





    public function update(Request $request, $id)
    {

        DB::transaction(function() use ($request, $id) {
            $report = Report::findOrFail($id);

            $report->update([
                'issue_type_id' => $request->report_type_id,
                'status'        => $request->status,
                'department_id' => $request->section_id,
            ]);

            // حذف البنود القديمة مع الميديا المرتبطة بها
            foreach ($report->items as $oldItem) {
                $oldItem->media()->delete();
            }
            $report->items()->delete();

            // إضافة البنود الجديدة
            if ($request->items) {
                foreach ($request->items as $index => $item) {
                    [$itemNo, $itemType] = explode('-', $item['item_no']);
                    $reportItem = ReportItem::create([
                        'report_id'         => $report->id,
                        'item_no'           => $itemNo,
                        'item_type'         => $itemType,
                        'control_unit_id'   => $item['control_unit_id'],
                        'user_control_unit' => $item['user_control_unit'] ?? null,
                        'causer_id'         => $item['causer_id'] ?? null,
                        'issue_description' => $item['issue_description'],
                        'response_status'   => $item['response_status'] ?? null,
                        'branch_id'         => 1,
                    ]);

                    // صورة واحدة
                    if ($request->hasFile("items.$index.control_unit_photo")) {
                        $photo = $request->file("items.$index.control_unit_photo");
                        $path = $photo->store('uploads/report_items', 'public');

                        Media::create([
                            'item_id' => $reportItem->id,
                            'url'     => $path,
                            'type'    => 'ReportItem',
                        ]);
                    }

                    // صور متعددة
                    if ($request->hasFile("items.$index.control_unit_photos")) {
                        foreach ($request->file("items.$index.control_unit_photos") as $multiPhoto) {
                            $path = $multiPhoto->store('uploads/report_items', 'public');

                            Media::create([
                                'item_id' => $reportItem->id,
                                'url'     => $path,
                                'type'    => 'ReportItem',
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('reports.index')->with('success', 'تم تعديل التقرير بنجاح ✅');
    }

    public function show($id)
    {
        // جلب التقرير مع البنود والعلاقات المطلوبة
        $report = Report::with([
            'reportType',
            'department',
            'user',
            'items' => function($query) {
                $query->with([
                    'controlUnit',
                    'causer',
                    'media',
                    'item',


                ]);
            }
        ])->findOrFail($id);

        return view('alerts.show', compact('report'));
    }
    public function destroy($id,$department)
    {
        $report = Report::findOrFail($id);
        $report->items()->delete();
        $report->delete();
        return redirect()->route('reports.index',$department)->with('success', 'تم حذف التقرير بنجاح ❌');
    }
}
