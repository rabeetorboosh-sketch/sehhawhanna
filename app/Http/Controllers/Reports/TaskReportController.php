<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TaskAssignment;
use App\Models\TaskOccurrence;
use App\Models\User;
use Illuminate\Http\Request;
use function Pest\Laravel\get;

class TaskReportController extends Controller
{
    public  function index(Request $request)
    {





        $employees=Employee::with('item')->get();
        $users=User::all();

        $assignments = TaskAssignment::with(['task.item', 'employee', 'days','occurrences.receipt']);


// فلترة من تاريخ
        if ($request->filled('solved')   ) {
            if ($request->input('solved')==1)
                $assignments =  $assignments->whereHas('receipt');
            if ($request->input('solved')==2)
                $assignments = $assignments->whereDoesntHave('receipt');
        }
        if ($request->filled('trans')) {
            if ($request->input('trans') == 1) {
                // المحولة للإدارة
                $assignments = $assignments->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 1);
                });
            }

            if ($request->input('trans') == 2) {
                // اللي ما تحولت للإدارة
                $assignments = $assignments->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 0);
                });
            }
        }
        if ($request->filled('responsiveness')) {
            if ($request->input('responsiveness') == 1) {
                // اللي تجاوبها متأخر (استلام بعد due_date) أو ما تسلمت وموعدها فات
                $assignments = $assignments->where(function($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '<', now())
                        ->orWhereHas('receipt', function($sub) {
                            $sub->whereColumn('task_receipts.created_at', '>', 'task_assignments.due_date');
                        });
                });
            }

            if ($request->input('responsiveness') == 2) {
                // اللي تجاوبها بالوقت (استلام قبل due_date)
                $assignments = $assignments->where(function($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '>=', now())
                        ->orWhereHas('receipt', function($sub) {
                            $sub->whereColumn('task_receipts.created_at', '<=', 'task_assignments.due_date');
                        });
                });
            }
        }

        if ($request->filled('from_date')) {
            $assignments->whereDate('created_at', '>=', $request->from_date);
        }

// فلترة إلى تاريخ
        if ($request->filled('to_date')) {
            $assignments->whereDate('created_at', '<=', $request->to_date);
        }


            if ($request->filled('employee_id')) {
                $assignments->where('employee_id', $request->employee_id);
            }
            if ($request->filled('repeat')) {
                $assignments->where('recurrence_type', $request->repeat=='null'?null:$request->repeat);
            }
        if ($request->filled('user_id')) {
                $assignments->where('user_id', $request->user_id);
            }


        $assignments  =$assignments->orderBy('id','desc')->get();


        if($request->input('summary')==0){

            $url='index';
            return view('reports.tasks.by_detail',compact(

                'assignments',
                'employees',
                'users',
                'url',
            ));
        }
        else{

            $url='index';
            return view('reports.tasks.by_summary',compact(

                'assignments',
                'employees',
                'users',
                'url',
            ));
        }

    }
    public function byEmployeeSummary(Request $request)
    {
        $employeesQuery = Employee::with('item');

        if ($request->filled('employee_id')) {
            $employeesQuery->where('id', $request->employee_id);
        }

        $employees = $employeesQuery->get();
        $users = User::all();

        // بناء الاستعلام مع الـ relations الضرورية
        $assignmentsQuery = TaskAssignment::with(['task.item', 'employee', 'days', 'occurrences', 'receipt', 'occurrences.receipt']);

        //Occurrences مع العلاقات (نستخدمها لحساب التكرارات)
        // بدال ما تجيب كل الـ occurrences مباشرة
        $occurrenceQuery = TaskOccurrence::with(['assignment', 'receipt']);

// نطبّق نفس فلاتر الـ assignments
        if ($request->filled('employee_id')) {
            $occurrenceQuery->whereHas('assignment', function($q) use ($request) {
                $q->where('employee_id', $request->employee_id);
            });
        }

        if ($request->filled('user_id')) {
            $occurrenceQuery->whereHas('assignment', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->filled('from_date')) {
            $occurrenceQuery->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $occurrenceQuery->whereDate('date', '<=', $request->to_date);
        }

// نفس منطق solved, trans, responsiveness لو تبيه مطبق على occurrences كمان
// (ممكن نعمله لكن يحتاج if/else مشابه للـ assignments)

        $occurrence = $occurrenceQuery->get();

        // === تطبيق الفلاتر كما في كودك الأصلي ===
        if ($request->filled('solved')) {
            if ($request->input('solved') == 1) {
                $assignmentsQuery = $assignmentsQuery->whereHas('receipt');
            }
            if ($request->input('solved') == 2) {
                $assignmentsQuery = $assignmentsQuery->whereDoesntHave('receipt');
            }
        }

        if ($request->filled('trans')) {
            if ($request->input('trans') == 1) {
                $assignmentsQuery = $assignmentsQuery->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 1);
                });
            } elseif ($request->input('trans') == 2) {
                $assignmentsQuery = $assignmentsQuery->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 0);
                });
            }
        }

        if ($request->filled('responsiveness')) {
            if ($request->input('responsiveness') == 1) {
                $assignmentsQuery = $assignmentsQuery->where(function ($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '<', now())
                        ->orWhereHas('receipt', function ($sub) {
                            $sub->whereColumn('task_receipts.created_at', '>', 'task_assignments.due_date');
                        });
                });
            } elseif ($request->input('responsiveness') == 2) {
                $assignmentsQuery = $assignmentsQuery->where(function ($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '>=', now())
                        ->orWhereHas('receipt', function ($sub) {
                            $sub->whereColumn('task_receipts.created_at', '<=', 'task_assignments.due_date');
                        });
                });
            }
        }

        if ($request->filled('from_date')) {
            $assignmentsQuery->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $assignmentsQuery->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('employee_id')) {
            $assignmentsQuery->where('employee_id', $request->employee_id);
        }

        if ($request->filled('repeat')) {
            $assignmentsQuery->where('recurrence_type', $request->repeat == 'null' ? null : $request->repeat);
        }

        if ($request->filled('user_id')) {
            $assignmentsQuery->where('user_id', $request->user_id);
        }

        $assignments = $assignmentsQuery->orderBy('id', 'desc')->get();

        // إذا طُلب الملخّص (summary == 0) نحسب الإحصائيات لكل موظف هنا

            $summary = [];
            $totals = [
                'totalTasks' => 0,
                'totalReceived' => 0,
                'totalForwarded' => 0,
                'totalOverdue' => 0,
            ];

            foreach ($employees as $employee) {
                // مهام غير متكررة مخصّصة للموظف
                $empAssignments = $assignments->filter(fn ($a) => $a->employee_id == $employee->id && $a->recurrence_type === null);

                // التكرارات (occurrences) المرتبطة بمهام الموظف
                $empOccurrences = $occurrence->filter(function ($o) use ($employee) {
                    return optional($o->assignment)->employee_id == $employee->id;
                });

                $totalCount = $empAssignments->count() + $empOccurrences->count();

                $completedCount = $empAssignments->filter(fn ($a) => $a->receipt->isNotEmpty())->count()
                    + $empOccurrences->filter(fn ($o) => $o->receipt->isNotEmpty())->count();

                $forwardedCount = $empAssignments->filter(fn ($a) => $a->receipt->contains('forwarded_to_management', 1))->count()
                    + $empOccurrences->filter(fn ($o) => $o->receipt->contains('forwarded_to_management', 1))->count();

                $overdueCount = $empAssignments->filter(fn ($a) => $a->isOverdue())->count()
                    + $empOccurrences->filter(fn ($o) => $o->isOverdue())->count();

                // حساب "التقييم" بجمع أي حقل رقمي متوقع في الـ receipt (rating, total_rate, score) — إن وُجد
                $score = 0.0;
                foreach ($empAssignments as $a) {
                    foreach ($a->receipt as $r) {
                        $score += (float) ($r->completion_percentage?? 0);
                    }
                }
                foreach ($empOccurrences as $o) {
                    foreach ($o->receipt as $r) {
                        $score += (float) ($r->completion_percentage?? 0);
                    }
                }

                $completionPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

                $summary[$employee->id] = [
                    'total' => $totalCount,
                    'completed' => $completedCount,
                    'forwarded' => $forwardedCount,
                    'overdue' => $overdueCount,
                    'score' => $score /((float)$completedCount==0?1:(float)$completedCount),
                    'completion_percentage' => $completionPercentage,
                ];

                // تجميع إجمالي الأرقام للملخّص السفلي
                $totals['totalTasks'] += $totalCount;
                $totals['totalReceived'] += $completedCount;
                $totals['totalForwarded'] += $forwardedCount;
                $totals['totalOverdue'] += $overdueCount;
            }
            $url='byEmployeeSummary';

            return view('reports.tasks.byEmployee_summary', compact(
                'assignments',
                'employees',
                'users',
                'occurrence', // حافظت على اسم المتغيّر القديم للـ view
                'summary',
                'url',
                'totals'
            ));

    }


    public function byEmployeeDetail(Request $request, $id)
    {
        $employee = Employee::with('item')->findOrFail($id);
        $users = User::all();

        $assignments = TaskAssignment::with(['task.item', 'employee', 'days', 'occurrences.receipt'])
            ->where('employee_id', $id);

        // فلترة حالة الإنجاز (تم الاستلام / لم يتم)
        if ($request->filled('solved')) {
            if ($request->input('solved') == 1) {
                $assignments->whereHas('receipt');
            } elseif ($request->input('solved') == 2) {
                $assignments->whereDoesntHave('receipt');
            }
        }

        // فلترة التحويل للإدارة
        if ($request->filled('trans')) {
            if ($request->input('trans') == 1) {
                $assignments->whereHas('receipt', fn($q) => $q->where('forwarded_to_management', 1));
            } elseif ($request->input('trans') == 2) {
                $assignments->whereHas('receipt', fn($q) => $q->where('forwarded_to_management', 0));
            }
        }

        // فلترة سرعة الاستجابة
        if ($request->filled('responsiveness')) {
            if ($request->input('responsiveness') == 1) {
                // المتأخرة
                $assignments->where(function ($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '<', now())
                        ->orWhereHas('receipt', function ($sub) {
                            $sub->whereColumn('task_receipts.created_at', '>', 'task_assignments.due_date');
                        });
                });
            } elseif ($request->input('responsiveness') == 2) {
                // في الوقت
                $assignments->where(function ($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '>=', now())
                        ->orWhereHas('receipt', function ($sub) {
                            $sub->whereColumn('task_receipts.created_at', '<=', 'task_assignments.due_date');
                        });
                });
            }
        }

        // ✅ فلترة من تاريخ
        if ($request->filled('from_date')) {
            $assignments->whereDate('created_at', '>=', $request->from_date);
        }

        // ✅ فلترة إلى تاريخ
        if ($request->filled('to_date')) {
            $assignments->whereDate('created_at', '<=', $request->to_date);
        }

        // ✅ فلترة حسب المستخدم (المسند)
        if ($request->filled('user_id')) {
            $assignments->where('user_id', $request->user_id);
        }

        // باقي الفلاتر إن وُجدت
        if ($request->filled('employee_id')) {
            $assignments->where('employee_id', $request->employee_id);
        }
        if ($request->filled('repeat')) {
            $assignments->where('recurrence_type', $request->repeat == 'null' ? null : $request->repeat);
        }

        $assignments = $assignments->orderByDesc('id')->get();

        $url = 'index';

        // عرض الصفحة
        return view('reports.tasks.byEmployee_detail', compact(
            'assignments',
            'employee',
            'users',
            'url',
            'id'
        ));
    }

    public  function print(Request $request)
    {





        $employees=Employee::with('item')->get();
        $users=User::all();

        $assignments = TaskAssignment::with(['task.item', 'employee', 'days']);


// فلترة من تاريخ
        if ($request->filled('solved')   ) {
            if ($request->input('solved')==1)
                $assignments =  $assignments->whereHas('receipt');
            if ($request->input('solved')==2)
                $assignments = $assignments->whereDoesntHave('receipt');
        }
        if ($request->filled('trans')) {
            if ($request->input('trans') == 1) {
                // المحولة للإدارة
                $assignments = $assignments->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 1);
                });
            }

            if ($request->input('trans') == 2) {
                // اللي ما تحولت للإدارة
                $assignments = $assignments->whereHas('receipt', function ($q) {
                    $q->where('forwarded_to_management', 0);
                });
            }
        }
        if ($request->filled('responsiveness')) {
            if ($request->input('responsiveness') == 1) {
                // اللي تجاوبها متأخر (استلام بعد due_date) أو ما تسلمت وموعدها فات
                $assignments = $assignments->where(function($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '<', now())
                        ->orWhereHas('receipt', function($sub) {
                            $sub->whereColumn('task_receipts.created_at', '>', 'task_assignments.due_date');
                        });
                });
            }

            if ($request->input('responsiveness') == 2) {
                // اللي تجاوبها بالوقت (استلام قبل due_date)
                $assignments = $assignments->where(function($q) {
                    $q->whereDoesntHave('receipt')
                        ->where('due_date', '>=', now())
                        ->orWhereHas('receipt', function($sub) {
                            $sub->whereColumn('task_receipts.created_at', '<=', 'task_assignments.due_date');
                        });
                });
            }
        }

        if ($request->filled('from_date')) {
            $assignments->whereDate('created_at', '>=', $request->from_date);
        }

// فلترة إلى تاريخ
        if ($request->filled('to_date')) {
            $assignments->whereDate('created_at', '<=', $request->to_date);
        }


            if ($request->filled('employee_id')) {
                $assignments->where('employee_id', $request->employee_id);
            }
            if ($request->filled('repeat')) {
                $assignments->where('recurrence_type', $request->repeat=='null'?null:$request->repeat);
            }
        if ($request->filled('user_id')) {
                $assignments->where('user_id', $request->user_id);
            }


        $assignments  =$assignments->orderBy('id','desc')->get();


        if($request->input('summary')==0){


            return view('reports.tasks.printing.by_detail',compact(

                'assignments',
                'employees',
                'users',
            ));
        }
        else{


            return view('reports.tasks.by_summary',compact(

                'assignments',
                'employees',
                'users',
            ));
        }

    }
}
