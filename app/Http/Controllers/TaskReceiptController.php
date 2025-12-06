<?php

namespace App\Http\Controllers;

use App\Models\DailyControlItem;
use App\Models\File;
use App\Models\Media;
use App\Models\ReportItem;
use App\Models\TaskReceipt;
use App\Models\TaskAssignment;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskReceiptController extends Controller
{
    /**
     * عرض كل الاستلامات
     */
    public function index()
    {
        $receipts = TaskReceipt::with(['assignment.task', 'employee'])->latest()->get();
        return view('taskReceipt.index', compact('receipts'));
    }

    /**
     * نموذج إنشاء استلام جديد
     */
    public function create()
    {

        session(['old_url_receipt' => url()->previous()]);

        $assignments = TaskAssignment::with([
            'employee.item',
            'task' => function ($morphTo) {
                $morphTo->morphWith([
                    ReportItem::class => ['controlUnit'],
                    DailyControlItem::class => ['controlUnit'],
                ]);
            }
        ])->get();
        $employees = Employee::all();
        $id="";
        return view('taskReceipt.create', compact('assignments', 'employees','id'));
    }

    /**
     * تخزين الاستلام الجديد
     */
    public function store(Request $request)
    {


        $data = $request->validate([
            'task_assignment_id' => 'required|exists:task_assignments,id',
            'received_at' => 'nullable',
            'is_completed' => 'nullable|boolean',
            'forwarded_to_management' => 'nullable|boolean',
            'forward_reason' => 'nullable|string|required_if:forwarded_to_management,1',
            'notes' => 'nullable|string',
            'location' => 'nullable|string',
            'solution_method' => 'nullable|string',
            'occurrence' => 'nullable|string',
        ]);

        $data['employee_id'] = User::with('employee')->find(auth()->id())->employee->id;

        $task_assignment = TaskAssignment::with('occurrences')->find($data['task_assignment_id']);

        $occurrences = $request->input('occurrence')??null;

        // ضبط القيم الافتراضية للـ checkbox
        $data['is_completed'] = $request->has('is_completed') ? 1 : 0;
        $data['forwarded_to_management'] = $request->has('forwarded_to_management') ? 1 : 0;
        $data['task_occurrence_id'] = $occurrences ??null;


        $taskReceipt=TaskReceipt::create($data);

        if ($request->hasFile("images")) {
            foreach ($request->file("images") as $multiPhoto) {
                $path = $multiPhoto->store('uploads/TaskReceipt', 'public');
                Media::create([
                    'item_id' => $taskReceipt->id,
                    'url'     => $path,
                    'type'    => 'TaskReceipt',
                ]);
            }
        }

        if ($request->hasFile("file-docs")) {
            foreach ($request->file("file-docs") as $file) {

                $path = $file->store('uploads/TaskReceipt', 'public');
                File::create([
                    'item_id' => $taskReceipt->id,
                    'url'     => $path,
                    'type'    => 'TaskReceipt',
                ]);
            }
        }

        return redirect()->to(session('old_url_receipt'))->with('success', 'تمت إضافة المهمة بنجاح ✅');

    }

    /**
     * عرض تفاصيل استلام مهمة
     */
    public function show(TaskReceipt $taskReceipt)
    {
        if(!Str::contains(url()->previous(), 'task_receipts/'))
        session(['old_TaskReceipt_url' => url()->previous()]);
        $taskReceipt->load('assignment.task.item', 'employee','media');
        return view('taskReceipt.show', compact('taskReceipt'));
    }


    /**
     * نموذج تعديل الاستلام
     */
    public function edit(TaskReceipt $taskReceipt)
    {
        $assignments = TaskAssignment::with('task.item')->get();
        return view('taskReceipt.edit', [
            'taskReceipt' => $taskReceipt,
            'assignments' => $assignments
        ]);
    }

    /**
     * تحديث الاستلام
     */
    public function update(Request $request, TaskReceipt $taskReceipt)
    {

        $data = $request->validate([
            'task_assignment_id' => 'required|exists:task_assignments,id',
            'received_at' => 'nullable|date',
            'is_completed' => 'nullable|boolean',
            'forwarded_to_management' => 'nullable|boolean',
            'forward_reason' => 'nullable|string|required_if:forwarded_to_management,1',
            'notes' => 'nullable|string',
            'location' => 'nullable|string',
            'solution_method' => 'nullable|string',
        ]);


        $data['is_completed'] = $request->has('is_completed') ? 1 : 0;
        $data['forwarded_to_management'] = $request->has('forwarded_to_management') ? 1 : 0;

        $taskReceipt->update($data);
        if ($request->hasFile("file-docs")) {
            foreach ($request->file("file-docs") as $file) {

                $path = $file->store('uploads/TaskReceipt', 'public');
                File::create([
                    'item_id' => $taskReceipt->id,
                    'url'     => $path,
                    'type'    => 'TaskReceipt',
                ]);
            }
        }
        return redirect()->route('task_receipts.index')
            ->with('success', 'تم تعديل استلام المهمة بنجاح');
    }

    /**
     * حذف الاستلام
     */
    public  function receipt($id,$occurrence=null)
    {

        session(['old_url_receipt' => url()->previous()]);
        $assignments =  TaskAssignment::with([
            'employee.item',
            'task' => function ($morphTo) {
                $morphTo->morphWith([
                    ReportItem::class => ['controlUnit'],
                    DailyControlItem::class => ['controlUnit'],
                ]);
            }
        ])->get();

        $employees = Employee::all();


    return view('taskReceipt.create', compact('assignments', 'employees','id','occurrence'));
    }

    // حذف استلام
    public function destroy(TaskReceipt $taskReceipt)
    {
        $taskReceipt->delete();
        return redirect()->route('task_receipts.index')->with('success', 'تم حذف الاستلام بنجاح');
    }
    public function rate(TaskReceipt $taskReceipt,Request $request)
    {

        $request->validate([
            'percentage' => 'required|integer|min:1|max:100',
        ]);
        if ($request->percentage){

            $taskReceipt->completion_percentage =$request->percentage ;
            $taskReceipt->save() ;

        }
        return redirect()->back();
    }
}
