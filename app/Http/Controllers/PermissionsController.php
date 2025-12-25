<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Package;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{

 public  function index()
 {
     $users =User::with('packages.templates')->paginate(20);

     return view('admin.permissions.index',compact('users'));

 }
  public function create()
    {

$departments=Department::all();
$packages=Package::all();
        $users = User::whereDoesntHave('packages')
            ->orWhereDoesntHave('templates')
            ->get();

return view('admin.permissions.create',compact('departments','users','packages'));
    }

    public function store(Request $request)
    {


        $user =User::with('packages.templates')->findOrFail($request->user_id);
        $package =Package::find($request->package_id);
        DB::transaction(function () use ($request ,$user,$package) {

                if ($package){
                    $user->packages()->attach($package->id);
                }

            $departments = Department::all();

            foreach ($departments as $department) {
                $deptName = strtolower($department->id);

                if ($request->has($deptName)) {
                    $deptData = $request->$deptName;


                    if (isset($deptData['insertions'])) {
                        foreach ($deptData['insertions'] as $model => $perms) {
                            $template = Template::create([
                                'model'         => $deptName.'-'.'insertions'.'-'.$model,
                                'can_create'    => $perms['create'] ?? 0,
                                'can_update'    => $perms['edit'] ?? 0,
                                'can_delete'    => $perms['delete'] ?? 0,
                                'can_approve'   => $perms['approve'] ?? 0,
                                'can_show'      => $perms['view'] ?? 0,
                            ]);
                            $user->templates()->attach($template->id);
                        }
                    }

                    // Process operations if present
                    if (isset($deptData['operations'])) {
                        foreach ($deptData['operations'] as $model => $perms) {
                            $template = Template::create([
                                'model'         => $deptName.'-'.'operations'.'-'.$model,
                                'can_create'    => $perms['create'] ?? 0,
                                'can_update'    => $perms['edit'] ?? 0,
                                'can_delete'    => $perms['delete'] ?? 0,
                                'can_approve'   => $perms['approve'] ?? 0,
                                'can_show'      => $perms['view'] ?? 0,
                            ]);
                            $user->templates()->attach($template->id);
                        }
                    }
                }

            }
            if ($request->has('pur')) {
                $deptData = $request->$deptName;


                if (isset($deptData['insertions'])) {
                    foreach ($deptData['insertions'] as $model => $perms) {
                        $template = Template::create([
                            'model'         =>  'pur-insertions'.'-'.$model,
                            'can_create'    => $perms['create'] ?? 0,
                            'can_update'    => $perms['edit'] ?? 0,
                            'can_delete'    => $perms['delete'] ?? 0,
                            'can_approve'   => $perms['approve'] ?? 0,
                            'can_show'      => $perms['view'] ?? 0,
                        ]);
                        $user->templates()->attach($template->id);
                    }
                }

                // Process operations if present
                if (isset($deptData['operations'])) {
                    foreach ($deptData['operations'] as $model => $perms) {
                        $template = Template::create([
                            'model'         =>  'pur-operations'.'-'.$model,
                            'can_create'    => $perms['create'] ?? 0,
                            'can_update'    => $perms['edit'] ?? 0,
                            'can_delete'    => $perms['delete'] ?? 0,
                            'can_approve'   => $perms['approve'] ?? 0,
                            'can_show'      => $perms['view'] ?? 0,
                        ]);
                        $user->templates()->attach($template->id);
                    }
                }
            }
            if ($request->has('general')){
                $deptData = $request->general;
                foreach ($deptData as $model => $perms) {
                    $template = Template::create([
                        'model'         => 'general'.'-'.$model,
                        'can_create'    => $perms['create'] ?? 0,
                        'can_update'    => $perms['edit'] ?? 0,
                        'can_delete'    => $perms['delete'] ?? 0,
                        'can_approve'   => $perms['approve'] ?? 0,
                        'can_show'      => $perms['view'] ?? 0,
                    ]);
                    $user->templates()->attach($template->id);
                }
            }
            if ($request->has('daily_monitoring')){
                $deptData = $request->daily_monitoring;
                foreach ($deptData as $model => $perms) {
                    $template = Template::create([
                        'model'         => 'daily_monitoring'.'-'.$model,
                        'can_create'    => $perms['create'] ?? 0,
                        'can_update'    => $perms['edit'] ?? 0,
                        'can_delete'    => $perms['delete'] ?? 0,
                        'can_approve'   => $perms['approve'] ?? 0,
                        'can_show'      => $perms['view'] ?? 0,
                    ]);
                    $user->templates()->attach($template->id);
                }
            }
        });


        return redirect()->route('users.index')->with('success', 'تمت إضافة الحزمة');
    }


    public function show($id)
    {

        $user = User::with('packages.templates','templates')->findOrFail($id);



        $departments = Department::pluck('name', 'id')->toArray();
        $departments['general'] = 'مدخلات عامة'  ;
        $departments['pur'] = 'المشتريات'  ;
        $departments['daily_monitoring'] = 'الرقابة اليومية'  ;
        return view('admin.permissions.show', compact('user','departments'));
    }

    public function edit($id)
    {
        $user=User::with('templates','packages.templates')->findOrFail($id);
        $packages = Package::all();
        $departments = Department::all();

        // تحويل القوالب إلى هيكل يمكن استخدامه في العرض
        $permissions = [];

        foreach ($user->templates as $template) {
            $parts = explode('-', $template->model);

            if (count($parts) == 2) {
                // مثل: general-users
                $permissions[$parts[0]][$parts[1]] = [
                    'create' => $template->can_create,
                    'edit'   => $template->can_update,
                    'delete' => $template->can_delete,
                    'view'   => $template->can_show,
                    'approve'   => $template->can_approve,
                ];
            } elseif (count($parts) == 3) {
                // مثل: 1-insertions-products
                $permissions[$parts[0]][$parts[1]][$parts[2]] = [
                    'create' => $template->can_create,
                    'edit'   => $template->can_update,
                    'delete' => $template->can_delete,
                    'view'   => $template->can_show,
                    'approve'   => $template->can_approve,
                ];
            }
        }

        return view('admin.permissions.edit', compact('packages', 'departments', 'permissions','user'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::find($request->package_id);
        DB::transaction(function () use ($request, $id, $package) {

            $user = User::findOrFail($id);

            // حذف جميع القوالب المرتبطة ثم إعادة إنشائها
            $user->templates()->delete();
            $user->templates()->detach();

            // Correct way to detach packages
            $user->packages()->detach(); // Detach all packages

            if ($package) {
                $user->packages()->attach($package->id);
            }

            // ثم إعادة إنشاء القوالب كما في store
            $departments = Department::all();

            foreach ($departments as $department) {
                $deptName = strtolower($department->id);

                if ($request->has($deptName)) {
                    $deptData = $request->$deptName;

                    if (isset($deptData['insertions'])) {
                        foreach ($deptData['insertions'] as $model => $perms) {
                            $template = Template::create([
                                'model'         => $deptName.'-'.'insertions'.'-'.$model,
                                'can_create'    => $perms['create'] ?? 0,
                                'can_update'    => $perms['edit'] ?? 0,
                                'can_delete'    => $perms['delete'] ?? 0,
                                'can_approve'   => $perms['approve'] ?? 0,
                                'can_show'      => $perms['view'] ?? 0,
                            ]);
                            $user->templates()->attach($template->id);
                        }
                    }

                    if (isset($deptData['operations'])) {
                        foreach ($deptData['operations'] as $model => $perms) {
                            $template = Template::create([
                                'model'         => $deptName.'-'.'operations'.'-'.$model,
                                'can_create'    => $perms['create'] ?? 0,
                                'can_update'    => $perms['edit'] ?? 0,
                                'can_delete'    => $perms['delete'] ?? 0,
                                'can_approve'   => $perms['approve'] ?? 0,
                                'can_show'      => $perms['view'] ?? 0,
                            ]);
                            $user->templates()->attach($template->id);
                        }
                    }
                }
            }
            if ($request->has('pur')) {
                $deptData = $request->pur;

                if (isset($deptData['insertions'])) {
                    foreach ($deptData['insertions'] as $model => $perms) {


                        $template = Template::create(array(
                            'model'         => 'pur-'.'insertions'.'-'.$model,
                            'can_create'    => $perms['create'] ?? 0,
                            'can_update'    => $perms['edit'] ?? 0,
                            'can_delete'    => $perms['delete'] ?? 0,
                            'can_approve'   => $perms['approve'] ?? 0,
                            'can_show'      => $perms['view'] ?? 0,
                        ));
                        $user->templates()->attach($template->id);
                    }
                }

                if (isset($deptData['operations'])) {
                    foreach ($deptData['operations'] as $model => $perms) {
                        $template = Template::create([
                            'model'         =>  'pur-'.'operations'.'-'.$model,
                            'can_create'    => $perms['create'] ?? 0,
                            'can_update'    => $perms['edit'] ?? 0,
                            'can_delete'    => $perms['delete'] ?? 0,
                            'can_approve'   => $perms['approve'] ?? 0,
                            'can_show'      => $perms['view'] ?? 0,
                        ]);
                        $user->templates()->attach($template->id);
                    }
                }
            }
            if ($request->has('general')) {
                $deptData = $request->general;
                foreach ($deptData as $model => $perms) {
                    $template = Template::create([
                        'model'         => 'general'.'-'.$model,
                        'can_create'    => $perms['create'] ?? 0,
                        'can_update'    => $perms['edit'] ?? 0,
                        'can_delete'    => $perms['delete'] ?? 0,
                        'can_approve'   => $perms['approve'] ?? 0,
                        'can_show'      => $perms['view'] ?? 0,
                    ]);
                    $user->templates()->attach($template->id);
                }
            }

            if ($request->has('daily_monitoring')) {
                $deptData = $request->daily_monitoring;
                foreach ($deptData as $model => $perms) {
                    $template = Template::create([
                        'model'         => 'daily_monitoring'.'-'.$model,
                        'can_create'    => $perms['create'] ?? 0,
                        'can_update'    => $perms['edit'] ?? 0,
                        'can_delete'    => $perms['delete'] ?? 0,
                        'can_approve'   => $perms['approve'] ?? 0,
                        'can_show'      => $perms['view'] ?? 0,
                    ]);
                    $user->templates()->attach($template->id);
                }
            }
        });

        return redirect()->route('users.index')->with('success', 'تم تعديل الحزمة بنجاح');
    }
}
