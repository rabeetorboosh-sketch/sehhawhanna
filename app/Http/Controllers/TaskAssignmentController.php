<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateTaskOccurrences;
use App\Models\ControlUnit;
use App\Models\DailyControl;
use App\Models\DailyControlItem;
use App\Models\Department;
use App\Models\Item;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Task;
use App\Models\Employee;
use App\Models\TaskAssignment;
use App\Models\TaskAssignmentDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskAssignmentController extends Controller
{
    /**
     * عرض جميع الإسنادات
     */
    public function index()
    {
        if(Auth::user()->isAdmin())
        $assignments = TaskAssignment::with(['task.item', 'employee', 'days'])->latest()->paginate(15);
        else
        $assignments = TaskAssignment::with(['task.item', 'employee', 'days'])->where('employee_id',Auth::user()?->employee?->id)->latest()->paginate(15);

        return view('task_assignments.index', compact('assignments'));
    }
    public function list(Request $request)
    {

        $tasks = Task::when($request->from_date, function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->from_date);
        })->when($request->to_date, function ($q) use ($request) {
            $q->whereDate('day', '<=', $request->to_date);
        })->get();

        $monitorings = DailyControl::with([
            'user',
            'items' => function ($q) {
                $q->where('is_correct', '!=', 1);
            },
            'items.item',
            'items.controlUnit',
        ])
            ->whereHas('items', function ($q) {
                $q->where('is_correct', '!=', 1);
            })
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('day', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('day', '<=', $request->to_date);
            })
            ->when($request->item_id, function ($q) use ($request) {
                $q->whereHas('items', function ($itemQ) use ($request) {
                    $itemQ->where('item_id', $request->item_id)
                        ->where('is_correct', '!=', 1);
                });
            })
            ->when($request->control_unit_id, function ($q) use ($request) {
                $q->whereHas('items', function ($itemQ) use ($request) {
                    $itemQ->where('control_unit_id', $request->control_unit_id)
                        ->where('is_correct', '!=', 1);
                });
            })
            ->when($request->section_id, function ($q) use ($request) {
                $q->whereHas('items.controlUnit', function ($unitQ) use ($request) {
                    $unitQ->where('department_id', $request->section_id);
                });
            })
            ->when($request->assigned == 1, function ($q) {
                $q->whereDoesntHave('items.assignments');
            })
            ->orderBy('day', 'desc')
            ->get();




        $reports = Report::with(['items.item', 'items.controlUnit'])
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            })
            ->when($request->control_unit_id, function ($q) use ($request) {
                $q->whereHas('items', function ($itemQ) use ($request) {
                    $itemQ->where('control_unit_id', $request->control_unit_id);
                })->with(['items' => function ($itemQ) use ($request) {
                    $itemQ->where('control_unit_id', $request->control_unit_id);
                }]);
            })
            ->when($request->section_id, function ($q) use ($request) {
                $q->whereHas('items.controlUnit', function ($unitQ) use ($request) {
                    $unitQ->where('department_id', $request->section_id);
                })->with(['items' => function ($itemQ) use ($request) {
                    $itemQ->whereHas('controlUnit', function ($unitQ) use ($request) {
                        $unitQ->where('department_id', $request->section_id);
                    });
                }]);
            })
            ->when($request->item_id, function ($q) use ($request) {
                $q->whereHas('items', function ($itemQ) use ($request) {
                    $itemQ->where('item_no', $request->item_id);
                })->with(['items' => function ($itemQ) use ($request) {
                    $itemQ->where('item_no', $request->item_id);
                }]);
            })
            ->when($request->assigned == 1, function ($q) {
                $q->whereDoesntHave('items.assignments');
            })
            ->orderBy('created_at', 'desc')
            ->get();



        $departments  = Department::all();
        $controlUnits = ControlUnit::all();

        $items        = Item::with('mainGroup','subGroup')->get();
        $dailyControls = DailyControl::query();
        return view('task_assignments.task_list', compact('monitorings', 'tasks', 'reports','departments','items','controlUnits','dailyControls'));
    }


    /**
     * صفحة إنشاء جديد
     */
    public function create()
    {
        $tasks = Task::all();
        $reports=Report::with('items.item','items.controlUnit')->get();
        $monitorings= DailyControl::with(['items.item','items.controlUnit', 'user'])->orderBy('day', 'desc')->get();
        $employees = Employee::all();
        return view('task_assignments.create', compact('tasks',
            'employees'
        ,'reports'
        ,'monitorings'
        ));
    }

    /**
     * حفظ إسناد جديد
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'tasks'         => 'nullable',
            'reports'         => 'nullable',
            'monitorings'         => 'nullable',
            'employee_id'     => 'required|exists:employees,id',
            'assigned_at'     => 'required|date',
            'due_date'        => 'nullable|date|after_or_equal:assigned_at',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly',
            'days'            => 'array',
            'days.*'          => 'integer'
        ]);

        DB::transaction(function () use ( $validated, $request) {


            if (isset($validated['tasks'])){
            foreach ($validated['tasks'] as $task){
                $assignment = TaskAssignment::create([
                    'task_id'        => $task,
                    'task_type'        => Task::class,
                    'user_id'    => Auth::user()->id,
                    'employee_id'    => $validated['employee_id'],
                    'assigned_at'    => $validated['assigned_at'],
                    'due_date'       => $validated['due_date'] ?? null,
                    'status'         => 'new',
                    'recurrence_type'=> $validated['recurrence_type'] ?? null,
                ]);
                if (!empty($validated['days']) && $validated['recurrence_type'] !== 'daily') {
                    foreach ($validated['days'] as $day) {
                        TaskAssignmentDay::create([
                            'task_assignment_id' => $assignment->id,
                            'day_of_week'  => $validated['recurrence_type'] === 'weekly' ? $day : null,
                            'day_of_month' => $validated['recurrence_type'] === 'monthly' ? $day : null,
                        ]);
                    }
                }

            }}
            if (isset($validated['reports'])){
            foreach ($validated['reports'] as $task){
                $assignment = TaskAssignment::create([
                    'task_id'        => $task,
                    'task_type'        => ReportItem::class,
                    'user_id'    => Auth::user()->id,
                    'employee_id'    => $validated['employee_id'],
                    'assigned_at'    => $validated['assigned_at'],
                    'due_date'       => $validated['due_date'] ?? null,
                    'status'         => 'new',
                    'recurrence_type'=> $validated['recurrence_type'] ?? null,
                ]);
            }
        }
            if (isset($validated['monitorings'])){
            foreach ($validated['monitorings'] as $task){
                $assignment = TaskAssignment::create([
                    'task_id'        => $task,
                    'task_type'        => DailyControlItem::class,
                    'user_id'    => Auth::user()->id,
                    'employee_id'    => $validated['employee_id'],
                    'assigned_at'    => $validated['assigned_at'],
                    'due_date'       => $validated['due_date'] ?? null,
                    'status'         => 'new',
                    'recurrence_type'=> $validated['recurrence_type'] ?? null,
                ]);
            }
        }


            Artisan::call('tasks:generate-occurrences');

        });

        return redirect()->route('task_assignments.index')->with('success', 'تم إنشاء إسناد المهمة بنجاح ✅');
    }
    public function assign(Request $request)
    {

        $validate = $request->validate([
            'monitorings' => 'array|required_without_all:reports,tasks',
            'reports'     => 'array|required_without_all:monitorings,tasks',
            'tasks'       => 'array|required_without_all:monitorings,reports',
        ]);

        $tasks = Task::whereIn('id', $request->tasks??[])->get();
        $reports = Report::with('items.item', 'items.controlUnit')
            ->whereHas('items', function ($q) use ($request) {
                $q->whereIn('id', $request->reports??[]);
            })
            ->get();

        $monitorings = DailyControl::with([
            'items' => function ($q) use($request) {
                $q->whereIn('id', $request->monitorings??[]); // فلترة العناصر المعادة
            },
            'items.item',
            'items.controlUnit',
            'user'
        ])
            ->whereHas('items', function ($q) use($request) {
                $q->whereIn('id', $request->monitorings??[]); // فلترة DailyControl نفسه
            })
            ->orderBy('day', 'desc')
            ->get();


        $employees = Employee::all();
        return view('task_assignments.listCreate', compact('tasks',
            'employees'
            ,'reports'
            ,'monitorings'
        ));

    }

    /**
     * عرض مهمة مسندة
     */
    public function show(TaskAssignment $taskAssignment)
    {
     $assignment=   $taskAssignment->load(['task', 'employee', 'days']);
        return view('task_assignments.show', compact('assignment'));
    }

    /**
     * صفحة تعديل
     */
    public function edit(TaskAssignment $taskAssignment)
    {
        $tasks = Task::all();
        $reports = Report::with('items.item','items.controlUnit')->get();
        $monitorings = DailyControl::with(['items.item','items.controlUnit', 'user'])->orderBy('day', 'desc')->get();
        $employees = Employee::all();

        $assignment = $taskAssignment->load('days');

        return view('task_assignments.edit', compact(
            'assignment',
            'tasks',
            'employees',
            'reports',
            'monitorings'
        ));
    }


    public function update(Request $request, TaskAssignment $taskAssignment)
    {

        $validated = $request->validate([
            'task_id'        => 'required|integer',
            'task_type'      => 'nullable',
            'employee_id'    => 'required|exists:employees,id',
            'assigned_at'    => 'required|date',
            'due_date'       => 'nullable|date|after_or_equal:assigned_at',
            'status'         => 'required|in:new,in_progress,completed,cancelled',
            'recurrence_type'=> 'nullable|in:daily,weekly,monthly',
            'days'           => 'nullable',
            'days.*'         => 'nullable'
        ]);

        DB::transaction(function () use ($validated, $taskAssignment) {

            // حدد الموديل المناسب

            $taskAssignment->update([
                'task_id'        => $validated['task_id'],
                'task_type'      =>  $validated['task_type'],
                'employee_id'    => $validated['employee_id'],
                'assigned_at'    => $validated['assigned_at'],
                'due_date'       => $validated['due_date'] ?? null,
                'status'         => $validated['status'],
                'recurrence_type'=> $validated['recurrence_type'] ?? null,
            ]);

            // حدث الأيام
            $taskAssignment->days()->delete();

            if (!empty($validated['days']) && $validated['recurrence_type'] !== 'daily') {
                foreach ($validated['days'] as $day) {
                    TaskAssignmentDay::create([
                        'task_assignment_id' => $taskAssignment->id,
                        'day_of_week'  => $validated['recurrence_type'] === 'weekly' ? $day : null,
                        'day_of_month' => $validated['recurrence_type'] === 'monthly' ? $day : null,
                    ]);
                }
            }
        });

        return redirect()->route('task_assignments.index')->with('success', 'تم تحديث إسناد المهمة بنجاح ✅');
    }

    /**
     * حذف مهمة مسندة
     */
    public function destroy(TaskAssignment $taskAssignment)
    {
        $taskAssignment->days()->delete();
        $taskAssignment->occurrences()->delete();
        $taskAssignment->delete();
        return redirect()->route('task_assignments.index')->with('success', 'تم حذف إسناد المهمة ✅');
    }
    public function myTasks()
    {
        $user_id = Auth::id();
        $employee_id=Auth::user()?->employee?->id;
        if ($employee_id) {

            $todayWeekDay = (Carbon::now()->dayOfWeek==6?0:Carbon::now()->dayOfWeek+1);

            $todayMonthDay = Carbon::now()->day;      // 1 - 31
            $todayDate = Carbon::today()->toDateString();

            // المهام المسندة مباشرة (غير دورية + بدون استلام)
            $directAssignments = TaskAssignment::with(['task.item', 'employee', 'days'])
                ->where('employee_id', $employee_id)
                ->whereDoesntHave('receipt')     // ما عندها أي استلام
                ->whereDoesntHave('days')        // مش مرتبطة بأيام
                ->whereNull('recurrence_type')   // مش يومية
                ->get();

            // المهام الدورية (أيام الأسبوع/الشهر + اليومية)
            $periodicAssignments = TaskAssignment::with(['task.item', 'employee', 'days'])
                ->where('employee_id', $employee_id    )
                ->where(function ($query) use ($todayWeekDay, $todayMonthDay) {
                    $query->whereHas('days', function ($q) use ($todayWeekDay, $todayMonthDay) {
                        $q->where('day_of_week', $todayWeekDay)
                            ->orWhere('day_of_month', $todayMonthDay);
                    })
                        ->orWhere('recurrence_type', 'daily'); // تشمل اليومية
                })
                ->whereDoesntHave('receipt', function ($q) use ($todayDate) {
                    // إذا استلمها اليوم → تختفي
                    $q->whereDate('created_at', $todayDate);
                })
                ->get();

            return view('myTask.index', compact('directAssignments', 'periodicAssignments'));

        }
        else{

            return '<div>
لا يوجد موظف مرتبط بهذا المستخدم


</div>';
        }

    }
}
