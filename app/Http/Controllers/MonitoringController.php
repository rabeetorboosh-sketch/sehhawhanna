<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ControlUnit;
use App\Models\Customer;
use App\Models\DailyControl;
use App\Models\DailyControlItem;
use App\Models\Department;
use App\Models\Employee;
use App\Models\IssueType;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\Media;
use App\Models\Report;
use App\Models\SubGroup;
use App\Models\Supplier;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
   public function index()
   {
       if (Auth::user()->isAdmin())
       $dailyControls = DailyControl::with(['items.item', 'user'])->orderBy('id', 'desc')->get();
       else
          $dailyControls = DailyControl::where('user_id',Auth::user()->id)->with(['items.item', 'user'])->orderBy('id', 'desc')->get();

       return view('monitorings.index',compact('dailyControls'));
   }
   public function partCreate($dep_id='')
   {
       $today = now()->toDateString();
       $userId = auth()->id();
       $existingReport = DailyControl::where('user_id', $userId)
           ->where('day', $today)
           ->first();

       $allDepartments=Department::where('id',$dep_id)->get();
       $permittedDepartments=[];

       foreach ($allDepartments as $dept)
           if (Auth::user()->sectionsPermissions($dept->id)) $permittedDepartments[]=$dept->id;


       $departments = Department::whereIn('id',$permittedDepartments)->get();


       $mainGroups=MainGroup::with('department')->get();
       $subGroups=SubGroup::all();

       $items = Item::with('mainGroup')->get();
       $employees=Employee::with('item')->get();
       $controlUnit =ControlUnit::with('section')->where('daily_control',1)->get();
    if ($existingReport){
        $monitoring =$existingReport->load('items');
        $controlUnits = ControlUnit::all();
        $today = now()->toDateString();
        $isAnotherDay=$today>$monitoring?->created_at;
        return view('monitorings.edit', compact('monitoring', 'departments', 'controlUnits', 'items', 'mainGroups', 'subGroups', 'employees','isAnotherDay'));

    }
    else{
        return view('monitorings.create',compact('departments',
            'controlUnit',
            'employees',
            'departments',
            'employees',
            'mainGroups',
            'subGroups',
            'items',
            'existingReport',

        ));

    }


   }
   public function create()
   {
       $today = now()->toDateString();
       $userId = auth()->id();
       $existingReport = DailyControl::where('user_id', $userId)
           ->where('day', $today)
           ->first();

       $allDepartments=Department::all();
       $permittedDepartments=[];
       foreach ($allDepartments as $dept)
       if (Auth::user()->sectionsPermissions($dept->id)) $permittedDepartments[]=$dept->id;


       $departments = Department::whereIn('id',$permittedDepartments)->get();


       $mainGroups=MainGroup::with('department')->get();
       $subGroups=SubGroup::all();

       $items = Item::with('mainGroup')->get();


       $employees=Employee::with('item')->get();
       $controlUnit =ControlUnit::with('section')->where('daily_control',1)->get();
     return view('monitorings.create',compact('departments',
         'controlUnit',
         'employees',
         'departments',
         'employees',
         'mainGroups',
         'subGroups',
         'items',
         'existingReport',



     ));
   }
    public function store(Request $request)
    {


        DB::beginTransaction();

        try {
        $items = collect($request->input('items')); // الأصناف لكل وحدة
        $items_ids = collect($request->input('item_id')); // الأصناف لكل وحدة
        $causers = $request->input('causer_id');    // المتسبب لكل وحدة
        $descriptions = $request->input('issue_text'); // الملاحظات لكل وحدة
        $is_correct = $request->input('is_correct'); // الملاحظات لكل وحدة
        $image = $request->file('image'); // الملاحظات لكل وحدة
        $images = $request->file('images'); // الملاحظات لكل وحدة
        $branchId = auth()->user()->branch_id ?? 1;   // مثال: فرع المستخدم الحالي

        $daylyControl =DailyControl::create([
            'user_id' => Auth::user()->id,
            'day'     =>Carbon::today()->toDateString(),
            'branch_id' => $branchId,

        ]);

        foreach ($items_ids as $item_id){

            if ($items->has($item_id)){
                 foreach ($items[$item_id] as $item){

                     $daylyControlItem= DailyControlItem::create([
                          'dailyControl_id' => $daylyControl->id,
                          'control_unit_id' => $item_id,
                          'item_id' => $item,
                          'causer_id' => $causers[$item_id] ?? null,
                          'description' => $descriptions[$item_id] ?? null,
                          'branch_id' => $branchId,
                          'is_correct' => $is_correct[$item_id] ?? 0,
                      ]);

                     if ($request->hasFile("image.$item_id")) {

                         $photo = $request->file("image.$item_id");
                         $path = $photo->store('uploads/dailyControl_items', 'public');

                         Media::create([
                             'item_id' => $daylyControlItem->id,
                             'url'     => $path,
                             'type'    => 'DlyCtrlItem',
                         ]);
                     }

                     // صور متعددة
                     // صور متعددة
                     if ($request->hasFile("images.$item_id")) {

                         foreach ($request->file("images.$item_id") as $multiPhoto) {
                             $path = $multiPhoto->store('uploads/dailyControl_items', 'public');
                             Media::create([
                                 'item_id' => $daylyControlItem->id,
                                 'url'     => $path,
                                 'type'    => 'DlyCtrlItem',
                             ]);
                         }
                     }



                 }
            }
            elseif ((isset($descriptions[$item_id]) && $descriptions[$item_id] !== null)or  (isset($is_correct[$item_id]) && $is_correct[$item_id] !== null))
            {
                $daylyControlItem=   DailyControlItem::create([
                    'dailyControl_id' => $daylyControl->id,
                    'control_unit_id' => $item_id,
                    'causer_id' => $causers[$item_id] ?? null,
                    'description' => $descriptions[$item_id] ?? null,
                    'branch_id' => $branchId,
                    'is_correct' => $is_correct[$item_id] ?? 0,
                ]);

                if ($request->hasFile("image.$item_id")) {

                    $photo = $request->file("image.$item_id");
                    $path = $photo->store('uploads/dailyControl_items', 'public');

                    Media::create([
                        'item_id' => $daylyControlItem->id,
                        'url'     => $path,
                        'type'    => 'DlyCtrlItem',
                    ]);
                }


                if ($request->hasFile("images.$item_id")) {

                    foreach ($request->file("images.$item_id") as $multiPhoto) {
                        $path = $multiPhoto->store('uploads/dailyControl_items', 'public');
                        Media::create([
                            'item_id' => $daylyControlItem->id,
                            'url'     => $path,
                            'type'    => 'DlyCtrlItem',
                        ]);
                    }
                }

            }
        }
            DB::commit();
        return redirect()->route('monitoring.index')->with('success', 'تمت الإضافة بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حصل خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }
    public function show(DailyControl $monitoring)
    {
        $monitoring->load(['items.controlUnit', 'items.item', 'user']);
        return view('monitorings.show', compact('monitoring'));
    }


    public function edit(DailyControl $monitoring)
    {
        $today = now()->toDateString();
      $isAnotherDay=$today>$monitoring?->created_at;
        $allDepartments=Department::all();
        $permittedDepartments=[];
        foreach ($allDepartments as $dept)
            if (Auth::user()->sectionsPermissions($dept->id)) $permittedDepartments[]=$dept->id;


        $departments = Department::whereIn('id',$permittedDepartments)->get();

        $controlUnits = ControlUnit::all();
        $items = Item::all();
        $mainGroups =MainGroup::all();
        $subGroups = SubGroup::all();
        $employees = Employee::all();

        $monitoring->load('items');

        return view('monitorings.edit', compact('monitoring', 'departments', 'controlUnits', 'items', 'mainGroups', 'subGroups', 'employees','isAnotherDay'));
    }

    public function update(Request $request, DailyControl $monitoring)
    {
        $items = collect($request->input('items')); // الأصناف لكل وحدة
        $items_ids = collect($request->input('item_id')); // الوحدات
        $causers = $request->input('causer_id');
        $descriptions = $request->input('issue_text');
        $is_correct = $request->input('is_correct');
        $oldImages = $request->input('old_images', []); // الصور القديمة القادمة من الفورم
        $branchId = auth()->user()->branch_id ?? 1;

        // نحذف البنود القديمة قبل التحديث
        $monitoring->items()->delete();

        foreach ($items_ids as $item_id) {
            if ($items->has($item_id)) {
                foreach ($items[$item_id] as $item) {
                    $daylyControlItem = DailyControlItem::create([
                        'dailyControl_id' => $monitoring->id,
                        'control_unit_id' => $item_id,
                        'item_id' => $item,
                        'causer_id' => $causers[$item_id] ?? null,
                        'description' => $descriptions[$item_id] ?? null,
                        'branch_id' => $branchId,
                        'is_correct' => $is_correct[$item_id] ?? 0,
                    ]);

                    // 🔹 إعادة ربط الصور القديمة
                    if (!empty($oldImages[$item_id])) {
                        foreach ($oldImages[$item_id] as $url) {
                            $url = preg_replace('#^https?://[^/]+/storage/#', '', $url);
                            Media::create([
                                'item_id' => $daylyControlItem->id,
                                'url'     => $url,
                                'type'    => 'DlyCtrlItem',
                            ]);
                        }
                    }

                    // 🔹 صورة واحدة جديدة
                    if ($request->hasFile("image.$item_id")) {
                        $photo = $request->file("image.$item_id");
                        $path = $photo->store('uploads/dailyControl_items', 'public');

                        Media::create([
                            'item_id' => $daylyControlItem->id,
                            'url'     => $path,
                            'type'    => 'DlyCtrlItem',
                        ]);
                    }

                    // 🔹 صور متعددة جديدة
                    if ($request->hasFile("images.$item_id")) {
                        foreach ($request->file("images.$item_id") as $multiPhoto) {
                            $path = $multiPhoto->store('uploads/dailyControl_items', 'public');
                            Media::create([
                                'item_id' => $daylyControlItem->id,
                                'url'     => $path,
                                'type'    => 'DlyCtrlItem',
                            ]);
                        }
                    }
                }
            } elseif ((isset($descriptions[$item_id]) && $descriptions[$item_id] !== null) || (isset($is_correct[$item_id]) && $is_correct[$item_id] !== null)) {
                $daylyControlItem = DailyControlItem::create([
                    'dailyControl_id' => $monitoring->id,
                    'control_unit_id' => $item_id,
                    'causer_id' => $causers[$item_id] ?? null,
                    'description' => $descriptions[$item_id] ?? null,
                    'branch_id' => $branchId,
                    'is_correct' => $is_correct[$item_id] ?? 0,
                ]);

                // 🔹 إعادة ربط الصور القديمة
                if (!empty($oldImages[$item_id])) {
                    foreach ($oldImages[$item_id] as $url) {
                        $url = preg_replace('#^https?://[^/]+/storage/#', '', $url);
                        Media::create([
                            'item_id' => $daylyControlItem->id,
                            'url'     => $url,
                            'type'    => 'DlyCtrlItem',
                        ]);
                    }
                }

                // 🔹 صورة واحدة جديدة
                if ($request->hasFile("image.$item_id")) {
                    $photo = $request->file("image.$item_id");
                    $path = $photo->store('uploads/dailyControl_items', 'public');

                    Media::create([
                        'item_id' => $daylyControlItem->id,
                        'url'     => $path,
                        'type'    => 'DlyCtrlItem',
                    ]);
                }

                // 🔹 صور متعددة جديدة
                if ($request->hasFile("images.$item_id")) {
                    foreach ($request->file("images.$item_id") as $multiPhoto) {
                        $path = $multiPhoto->store('uploads/dailyControl_items', 'public');
                        Media::create([
                            'item_id' => $daylyControlItem->id,
                            'url'     => $path,
                            'type'    => 'DlyCtrlItem',
                        ]);
                    }
                }
            }
        }

        return redirect()->route('monitoring.index')->with('success', 'تم تحديث الرقابة اليومية بنجاح');
    }

    public function destroy(DailyControl $monitoring)
    {
        // حذف كل البنود المرتبطة أولاً
        $monitoring->items()->delete();
        $monitoring->delete();

        return redirect()->route('monitoring.index')->with('success', 'تم الحذف بنجاح');
    }
}
