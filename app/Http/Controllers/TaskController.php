<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Task;
use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {

        $tasks = Task::with(['item'])->get();
        return view('admin.task.index', compact('tasks'));
    }

    public function create()
    {

        session(['old_url' => url()->previous()]);

        $mainGroups = MainGroup::all();
        $subGroups  = SubGroup::all();

        return view('admin.task.create', compact('mainGroups', 'subGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'          => 'nullable|string|max:255',
            'main_group_id' => 'nullable|exists:main_groups,id',
            'sub_group_id'  => 'nullable|exists:sub_groups,id',
        ]);

        DB::transaction(function () use ($request ) {
            $item = Item::create([
                'name'          => $request['name'],
                'type'          => 'Task',
                'main_group_id' => $request['main_group_id'],
                'sub_group_id'  => $request['sub_group_id'],
                'department_id'  =>'5',
                'branch_id'     => 1,
            ]);

            Task::create([
                'item_id'          => $item->id,
                'description'          => $request->description,
                'branch_id'     => 1, // ثابت
            ]);
        });


        return redirect()->to(session('old_url'))->with('success', 'تمت إضافة المهمة بنجاح ✅');

    }

    public function edit(Task $task)
    {
        $mainGroups = MainGroup::all();
        $subGroups  = SubGroup::all();

        return view('admin.task.edit', compact('task', 'mainGroups', 'subGroups'));
    }

    public function update(Request $request,  $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'main_group_id' => 'nullable|exists:main_groups,id',
            'sub_group_id'  => 'nullable|exists:sub_groups,id',
        ]);

        DB::transaction(function () use ($request,$id) {
            $task = Task::with('item')->findOrFail($id);

            $task->item->update([
                'name' => $request['name'],
                'main_group_id' => $request['main_group_id'],
                'sub_group_id' => $request['sub_group_id'],
            ]);

$task->update([
    'description'    => $request->description,
        ]);


        });


        return redirect()->route('tasks.index')->with('success', 'تم تحديث بيانات المهمة بنجاح ✅');
    }

    public function destroy(Task $task)
    {
        $task->item->delete();
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'تم حذف المهمة بنجاح 🗑️');
    }
}
