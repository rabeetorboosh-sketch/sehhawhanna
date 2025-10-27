<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Package;
use App\Models\PackageTemplate;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {


        $packages = Package::withCount('templates') // يجيب عدد النماذج مباشرة
        ->orderBy('id', 'desc')  // يرتب من الأحدث
        ->paginate(30);          // 10 عناصر بالصفحة

        return view('admin.packages.index', compact('packages'));  }

    public function create()
    {

        $departments=Department::all();
        $packages=Package::all();
        return view('admin.packages.create',compact('departments','packages'));

    }

    public function store(Request $request)
    {



        DB::transaction(function () use ($request) {
            $pakage = Package::create([
                'name'        => $request['name'],
                'description' => $request['description'],
            ]);


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
                            $pakage->templates()->attach($template->id);
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
                            $pakage->templates()->attach($template->id);
                        }
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
                    $pakage->templates()->attach($template->id);
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
                    $pakage->templates()->attach($template->id);
                }
            }
        });
        return redirect()->route('packages.index')->with('success', 'تمت إضافة الحزمة');
    }

    public function show(Package $package)
    {
        $package->load('templates');


        $departments = Department::pluck('name', 'id')->toArray();
        $departments['general'] = 'مدخلات عامة'  ;
        $departments['daily_monitoring'] = 'الرقابة اليومية'  ;
        return view('admin.packages.show', compact('package','departments'));
    }
    public function edit($id)
    {
        $package = Package::with('templates')->findOrFail($id);
        $departments = Department::all();

        // تحويل القوالب إلى هيكل يمكن استخدامه في العرض
        $permissions = [];

        foreach ($package->templates as $template) {
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

        return view('admin.packages.edit', compact('package', 'departments', 'permissions'));
    }

    public function update(Request $request, $id)
    {


        DB::transaction(function () use ($request, $id) {
            $package = Package::findOrFail($id);
            $package->update([
                'name'        => $request['name'],
                'description' => $request['description'],
            ]);

            // حذف جميع القوالب المرتبطة ثم إعادة إنشائها
            $package->templates()->delete();
            $package->templates()->detach();

            // ثم إعادة إنشاء القوالب كما في store
            $departments = Department::all();

            foreach ($departments as $department) {
                $deptName = strtolower($department->id);

                if ($request->has($deptName)) {
                    $deptData = $request->$deptName;

                    if (isset($deptData['insertions'])) {
                        foreach ($deptData['insertions'] as $model => $perms) {


                            $template = Template::create(array(
                                'model'         => $deptName.'-'.'insertions'.'-'.$model,
                                'can_create'    => $perms['create'] ?? 0,
                                'can_update'    => $perms['edit'] ?? 0,
                                'can_delete'    => $perms['delete'] ?? 0,
                                'can_approve'   => $perms['approve'] ?? 0,
                                'can_show'      => $perms['view'] ?? 0,
                            ));
                            $package->templates()->attach($template->id);
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
                            $package->templates()->attach($template->id);
                        }
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
                    $package->templates()->attach($template->id);
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
                    $package->templates()->attach($template->id);
                }
            }
        });

        return redirect()->route('packages.index')->with('success', 'تم تعديل الحزمة بنجاح');
    }
    public function destroy(Package $package)
    {
        DB::transaction(function () use ($package) {
            // مسح Templates المرتبطة
            foreach($package->templates as $template) {
                $template->delete();
            }

            $package->delete();
        });

        return redirect()->route('packages.index')->with('success', 'تم حذف الحزمة');
    }

}
