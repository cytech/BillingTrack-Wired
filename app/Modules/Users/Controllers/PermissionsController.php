<?php

namespace BT\Modules\Users\Controllers;

use BT\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use BT\Traits\ReturnUrl;

class PermissionsController extends Controller
{
    use ReturnUrl;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create')->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|unique:permissions',
            'group'      => 'required',
            'guard_name' => 'required'
        ]);

        $permission = new Permission;
        $permission->name = $request->input('name');
        $permission->description = $request->input('description');
        $permission->group = $request->input('group');
        $permission->guard_name = $request->input('guard_name');
        $permission->save();

        return redirect()->route('users.manage_acl')->with(['success', 'Permission Saved']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'))
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->group = $request->group;
        $permission->guard_name = $request->guard_name;
        $permission->save();

        return redirect()->route('users.manage_acl')->with('success', 'Permission Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }

}
