<?php

namespace App\Http\Controllers\Panel;

use App\Models\Permission;
use App\MOdels\PermissionGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PermissionGroupController extends Controller
{

    public function index()
    {
        // $this->authorize('view permission groups');
        $groups = PermissionGroup::all();

        return view('panel.roles.groups.index')->with('groups', $groups);
    }

    public function create()
    {
        // $this->authorize('create permission group');
        return view('panel.roles.groups.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create permission group');
        $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name',
            'title' => 'required|string|max:255'
        ], [], [
            'name' => 'نام گروه',
            'title' => 'عنوان گروه'
        ]);

        PermissionGroup::create([
            'name' => $request->name,
            'title' => $request->title
        ]);

        return redirect()->route('panel.permissionGroups.index')
            ->with('success', 'گروه دسترسی جدید با موفقیت اضافه شد');
    }

    public function edit(PermissionGroup $permissionGroup)
    {
        // $this->authorize('edit permission group');
        return view('panel.roles.groups.edit')
            ->with('group', $permissionGroup);
    }

    public function update(Request $request, PermissionGroup $permissionGroup)
    {
        // $this->authorize('edit permission group');
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permission_groups', 'name')->ignore($permissionGroup->id)
            ],
            'title' => 'required|string|max:255'
        ], [], [
            'name' => 'نام گروه',
            'title' => 'عنوان گروه'
        ]);

        $permissionGroup->update([
            'name' => $request->name,
            'title' => $request->title
        ]);

        return redirect()->route('panel.permissionGroups.index')
            ->with('success', 'گروه دسترسی مورد نظر با موفقیت ویرایش شد');
    }

    public function destroy(PermissionGroup $permissionGroup)
    {
        // $this->authorize('delete permission group');
        if ($permissionGroup->permissions()->count()) {
            return back()->withErrors('لطفا ابتدا دسترسی های این گروه را حذف کنید');
        }

        $permissionGroup->delete();

        return redirect()->route('panel.permissionGroups.index')
            ->with('success', 'گروه دسترسی مورد نظر با موفقیت حذف شد');
    }
}
