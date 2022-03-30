<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Users\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\Users\Models\User;
use BT\Modules\Users\Requests\UserStoreRequest;
use BT\Modules\Users\Requests\UserUpdateRequest;
use BT\Traits\ReturnUrl;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ReturnUrl;


    public function index()
    {
        $this->setReturnUrl();

        return view('users.index',
            ['userTypes' => ['' => trans('bt.all_accounts'), 'admin' => trans('bt.admin_accounts'), 'client' => trans('bt.client_accounts')]]);

    }

    public function create($userType)
    {
        $view = view('users.' . $userType . '_form')
            ->with('roles', Role::where('name', '!=', 'superadmin')->get())
            ->with('permissions', Permission::all())
            ->with('editMode', false)
            ->with('customFields', CustomField::forTable('users')->get());

        return $view;
    }

    public function store(UserStoreRequest $request, $userType)
    {
        if ($userType == 'client') {
            $client = Client::find($request->client_id);
            $user = new User(['name' => $client->name, 'email' => $client->email, 'client_id' => $client->id]);
        } else {
            $user = new User($request->except('custom', 'roles', 'permissions'));
        }
        $user->password = $request->input('password');

        $user->save();
        if ($userType == 'client') {
            $user->assignRole('Client');
        } else {
            // Handle the user roles
            $this->syncPermissions($request, $user);
        }
        $user->custom->update($request->input('custom', []));

        return redirect($this->getReturnUrl())
            ->with('alertSuccess', trans('bt.record_successfully_created'));
    }

    public function edit($id, $userType)
    {
        $user = User::findOrFail($id);
        $permissions = Permission::all();
        $roles = Role::all();
        $user_roles = $user->getRoleNames();
        $user_permissions = $user->getDirectPermissions();

        $userType != 'superadmin' ?: $userType = 'admin';

        return view('users.' . $userType . '_form', compact(
            'roles',
            'permissions',
            'user_roles',
            'user_permissions'
        ))
            ->with(['editMode' => true, 'user' => $user])
            ->with('customFields', CustomField::forTable('users')->get());
    }

    public function update(UserUpdateRequest $request, $id, $userType)
    {
        $user = User::findOrFail($id);

        $user->fill($request->except('custom', 'roles', 'permissions'));

//        $user->save();
//        $user->name  = $request->name;
//        $user->email = $request->email;

        if ($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->save();
        // Handle the user roles
        $this->syncPermissions($request, $user);

        $user->custom->update($request->input('custom', []));

        return redirect(route('users.index'))
            ->with('alertInfo', trans('bt.record_successfully_updated'));
    }

    public function delete($id)
    {
        User::destroy($id);

        return redirect()->route('users.index')
            ->with('alert', trans('bt.record_successfully_deleted'));
    }

    public function clientLookup()
    {
        $clients = Client::whereDoesntHave('user')->select('id', 'name', 'unique_name', 'email')
            ->where('email', '<>', '')
            ->whereNotNull('email')
            ->where('active', 1)
            ->where('name', 'like', '%' . request('term') . '%')
            ->orderBy('name')
            ->get();

        $list = [];

        foreach ($clients as $client) {
            $list[] = ['id' => $client->id, 'name' => $client->name, 'unique_name' => $client->unique_name, 'email' => $client->email];
        }

        return json_encode($list);
    }

    private function syncPermissions(Request $request, $user)
    {

        // submitted roles / permissions
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if (!$user->hasAllRoles($roles)) {

            // reset all direct permissions for user
            // Bob - I have a potential problem with this - but we'll leave it for now
            $user->permissions()->sync([]);

        } else {
            // handle permissions
            $user->syncPermissions($permissions);

        }

        $user->syncRoles($roles);

        return $user;
    }


}
