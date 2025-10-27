<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // عرض قائمة المستخدمين
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // عرض نموذج إنشاء مستخدم جديد
    public function create()
    {
        return view('admin.users.create');
    }

    // حفظ مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rule' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rule' => $request->rule,
        ]);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    // عرض بيانات مستخدم محدد
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // عرض نموذج تعديل مستخدم
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // تحديث بيانات مستخدم
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'rule' => 'required|string',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->rule = $request->rule;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public  function permit($id)
    {
            $user =User::with('packages','templates')->findOrFail($id);
        $packages = Package::all();
        $departments = Department::all();


            if ( (isset($user->packages) or isset($user->templates)) and (!$user->templates->isEmpty() or !$user->packages->isEmpty())){

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

            }else{
                $users = User::where('id',$id)->get();

                return view('admin.permissions.create',compact('departments','users','packages'));

            }



            }


    // حذف مستخدم
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
