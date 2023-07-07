<?php

namespace App\Http\Controllers\Panel;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        // $this->authorize('view permissions');

        $permissions = Permission::orderBy('created_at', 'desc');

        if ($request->title) {
            $permissions = $permissions->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->title . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->title . '%');
            });
        }

        if ($request->group) {
            $permissions = $permissions->where('group_id', $request->group);
        }

        $permissions = $permissions->paginate();
        $permissions->appends($request->query());

        $groups = PermissionGroup::all();
        return view('panel.roles.permissions.index')
            ->with('groups', $groups)
            ->with('permissions', $permissions);
    }

    public function create()
    {
        // $this->authorize('create permission');
        $groups = PermissionGroup::all();
        return view('panel.roles.permissions.create')
            ->with('groups', $groups);
    }

    public function store(Request $request)
    {
        // $this->authorize('create permission');
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'title' => 'required|string|max:255',
            'group' => 'required|exists:permission_groups,id'
        ], [], [
            'name' => 'نام دسترسی',
            'title' => 'عنوان دسترسی',
            'group' => 'گروه دسترسی'
        ]);

        Permission::create([
            'name' => $request->name,
            'title' => $request->title,
            'group_id' => $request->group
        ]);

        return redirect()->route('panel.permissions.index')
            ->with('success', 'دسترسی جدبد با موفقیت اضافه شد');
    }

    public function edit(Permission $permission)
    {
        // $this->authorize('edit permission');
        $groups = PermissionGroup::all();
        return view('panel.roles.permissions.edit')
            ->with('groups', $groups)
            ->with('permission', $permission);
    }

    public function update(Request $request, Permission $permission)
    {
        // $this->authorize('edit permission');
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions', 'name')->ignore($permission->id)
            ],
            'title' => 'required|string|max:255',
            'group' => 'required|exists:permission_groups,id'
        ], [], [
            'name' => 'نام دسترسی',
            'title' => 'عنوان دسترسی',
            'group' => 'گروه دسترسی'
        ]);

        $permission->update([
            'name' => $request->name,
            'title' => $request->title,
            'group_id' => $request->group
        ]);

        return redirect()->route('panel.permissions.index')
            ->with('success', 'دسترسی مورد نظر با موفقیت ویرایش شد');
    }

    public function destroy(Permission $permission)
    {
        // $this->authorize('delete permission');
        $permission->delete();
        return redirect()->route('panel.permissions.index')
            ->with('success', 'دسترسی مورد نظر با موفقیت حذف شد');
    }
}
