<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc');
        if (request('search')) {
            $users = $users->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', "%$request->search%")
                    ->orWhere('email', 'LIKE', "%$request->search%")
                    ->orWhere('mobile', 'LIKE', "%$request->search%");
            });
        }

        if ($request->role) {
            $users = $users->whereHas('roles', function ($query) use ($request) {
                $query->where('id', $request->role);
            });
        }
        if (!($request->active === null)) {
            $users = $users->where('is_active', $request->active == 1);
        }

        $users = $users->paginate();
        $users->appends(request()->query());

        return view('panel.users.index')
            ->with('roles', Role::all())
            ->with('users', $users);
    }
    public function create()
    {
        return view('panel.users.create');
    }
    public function store(Request $request)
    {
        // $this->authorize('create user');
        $request->validate([
            'name' => 'string|max:255|nullable',
            'mobile' => 'required|regex:/^09\d{9}$/|unique:users',
            'email' => 'nullable|email|unique:users',
            'address' => 'nullable',
            'is_active' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ], [], [
            'name' => 'نام و نام خانوادگی',
            'mobile' => 'شماره تماس',
            'email' => 'ایمیل',
            'address' => 'آدرس',
            'is_active' => 'وضعیت',
            'password' => 'رمز عبور',
        ]);


        User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'is_active' => $request->is_active ? true : false,
            'password' => Hash::make($request->password),
        ]);

        return \redirect()->route('panel.users.index')->with([
            'success' => 'کاربر جدید با موفقیت ثبت شد'
        ]);
    }
    public function show(User $user)
    {
        // $this->authorize('view users');
        return view('panel.users.show')->with([
            'roles' => Role::all(),
            'permissions' => Permission::all(),
            'user' => $user
        ]);
    }
    public function edit(User $user)
    {
        // $this->authorize('edit user');
        return view('panel.users.edit')->with([
            'user' => $user
        ]);
    }

    public function update(User $user, Request $request)
    {
        // $this->authorize('edit user');

        $request->validate([
            'name' => 'nullable|string|max:255',
            'mobile' => 'required|regex:/^09\d{9}$/',
            'email' => 'nullable|email',
            'address' => 'nullable|max:2000',
            'is_active' => 'nullable',
            'password' => 'nullable|confirmed|min:6',
        ], [], [
            'name' => 'نام و نام خانوادگی',
            'mobile' => 'شماره تماس',
            'email' => 'ایمیل',
            'address' => 'آدرس',
            'is_active' => 'وضعیت',
            'password' => 'رمز عبور',
        ]);


        $user->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'is_active' => $request->is_active ? true : false,
            'password' => $request->password == null ?  $user->password : Hash::make($request->password)
        ]);

        return \redirect()->route('panel.users.index')->with([
            'success' => 'اطلاعات کاربر با موفقیت ویرایش شد'
        ]);
    }

    public function destroy(User $user)
    {
        // $this->authorize('delete user');

        $user->delete();
        return \redirect()->route('panel.users.index')->with([
            'danger' => 'کاربر مورد نظر با موفقیت حذف شد'
        ]);
    }

    public function changeActive(User $user)
    {
        // $this->authorize('edit user');
        $user->update([
            'is_active' => !$user->is_active
        ]);
        return \redirect()->back()->with([
            'sucess' => 'وضعیت کاربر تغییر با موفقیت تغییر کرد'
        ]);
    }


    public function addRole(Request $request, User $user)
    {
        $this->authorize('edit user permissions');
        $request->validate([
            'role' => 'required|exists:roles,name'
        ], [], [
            'role' => 'نقش'
        ]);

        $user->assignRole($request->role);
        return redirect()->route('panel.users.show', $user)->with([
            'success' => 'نقش مورد نظر به کاربر داده شد'
        ]);
    }

    public function removeRole(User $user, Role $role)
    {
        $this->authorize('edit user permissions');
        $user->removeRole($role->name);
        return redirect()->route('panel.users.show', $user)->with([
            'success' => 'نقش مورد نظر از کاربر گرفته شد'
        ]);
    }

    public function addPermission(Request $request, User $user)
    {
        $this->authorize('edit user permissions');
        $request->validate([
            'permission' => 'required|exists:permissions,name'
        ], [], [
            'permission' => 'دسترسی'
        ]);

        $user->givePermissionTo($request->permission);
        return redirect()->route('panel.users.show', $user)->with([
            'success' => 'دسترسی مورد نظر به کاربر داده شد'
        ]);
    }

    public function revokePermission(User $user, Permission $permission)
    {
        $this->authorize('edit user permissions');
        $user->revokePermissionTo($permission->name);
        return redirect()->route('panel.users.show', $user)->with([
            'success' => 'دسترسی مورد نظر از کاربر گرفته شد'
        ]);
    }
}
