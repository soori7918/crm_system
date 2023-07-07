<?php

namespace App\Http\Controllers\Panel;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('view roles');
        $roles = Role::all();
        return view('panel.roles.roles.index')->with('roles', $roles);
    }

    public function create()
    {
        $this->authorize('create role');
        return view('panel.roles.roles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create role');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^([A-Za-z])\w+$/', 'unique:roles,name'],
            'title' => 'required|string|max:255'
        ], [], [
            'name' => 'نام نقش',
            'title' => 'عنوان نقش'
        ]);
        $role = Role::create([
            'name' => $request->name,
            'title' => $request->title
        ]);
        return redirect()->route('panel.roles.index')->with('success', 'نقش مورد نظر شما با موفقیت ایجاد شد');
    }

    public function edit(Role $role)
    {
        $this->authorize('edit role');
        $groups = PermissionGroup::with('permissions')->get();
        return view('panel.roles.roles.edit')
            ->with('groups', $groups)
            ->with('role', $role);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('edit role');
        $request->validate([
            'name' => [
                'required', 'string', 'max:255', 'regex:/^([A-Za-z])\w+$/',
                Rule::unique('roles')->ignore($role->id),
            ],
            'title' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [], [
            'name' => 'نام نقش',
            'title' => 'عنوان نقش',
            'permissions' => 'دسترسی'
        ]);
        $role->update([
            'name' => $request->name,
            'title' => $request->title
        ]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('panel.roles.index')->with('success', 'نقش مورد نظر شما با موفقیت ویرایش شد');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete role');
        $role->delete();
        return redirect()->route('panel.roles.index')->with('success', 'نقش مورد نظر شما با موفقیت حذف شد');
    }
}
