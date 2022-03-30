<?php

namespace BT\Modules\Users\Controllers;

use BT\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use BT\Modules\Users\Models\Permission;
use BT\Traits\ReturnUrl;

class RolesController extends Controller
{
    use ReturnUrl;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setReturnUrl();

        $roles = Role::all();
        $permissions = Permission::orderBy('group')->get();
        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perms = Permission::all();
        $permsort = $perms->sortBy('perm_sub_group')->sortBy('perm_group');
        $permissions = $permsort->groupBy(['perm_group', 'perm_sub_group']);

        return view('roles.create', compact('permissions'))
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required',
            'guard_name' => 'required'
        ]);

        $role = new Role;
        $role->name = $request->input('name');
        $role->guard_name = $request->input('guard_name');
        $role->save();

        $permissions = $request->get('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('users.manage_acl')->with('success', 'Role Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $perms = Permission::all();
        // sort/group by perm_sub_group and perm_group extended Permission model accessors
        $permsort = $perms->sortBy('perm_sub_group')->sortBy('perm_group');
        $permissions = $permsort->groupBy(['perm_group', 'perm_sub_group']);

        return view('roles.edit', compact('role', 'permissions'))
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();

        $permissions = $request->get('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('users.manage_acl')->with('success', 'Role Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
