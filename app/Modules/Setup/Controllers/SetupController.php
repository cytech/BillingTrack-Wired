<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Setup\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Settings\Models\Setting;
use BT\Modules\Setup\Requests\LicenseRequest;
use BT\Modules\Setup\Requests\ProfileRequest;
use BT\Modules\Users\Models\User;
use BT\Support\Migrations;
use DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SetupController extends Controller
{
    private $migrations;

    public function __construct(Migrations $migrations)
    {
        $this->migrations = $migrations;
    }

    public function index()
    {
        return view('setup.index')
            ->with('license', file_get_contents(public_path('LICENSE')));
    }

    public function postIndex(LicenseRequest $request)
    {
        return redirect()->route('setup.prerequisites');
    }

    public function prerequisites()
    {
        $errors = [];
        $versionRequired = '8.1';
        $dbDriver = config('database.default');
        $dbConfig = config('database.connections.' . $dbDriver);

        if (version_compare(phpversion(), $versionRequired, '<')) {
            $errors[] = sprintf(trans('bt.php_version_error'), $versionRequired);
        }

        if (!$dbConfig['host'] or !$dbConfig['database'] or !$dbConfig['username'] or !$dbConfig['password']) {
            $errors[] = trans('bt.database_not_configured');
        }

        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        try {
            DB::select($query, [$dbConfig['database']]);
        } catch (\PDOException $e) {
            $errors[] = trans('bt.database_schema_not_configured');
        }

        if (!$errors) {
            return redirect()->route('setup.migration');
        }

        return view('setup.prerequisites')
            ->with('errors', $errors);
    }

    public function migration()
    {
        return view('setup.migration');
    }

    public function postMigration()
    {
        if ($this->migrations->runMigrations(database_path('migrations'))) {
            return response()->json([], 200);
        }

        return response()->json(['exception' => $this->migrations->getException()->getMessage()], 400);
    }

    public function account()
    {
        $role = Role::findByName('superadmin');

        if (!User::count()) {

            return view('setup.account');

        } elseif (!$role->users()->count()) {
            $users = User::whereNull('client_id')->orWhere('client_id', 0)->orderBy('id')->get();
            return view('setup.superadmin')->with('users', $users);
        }

        return redirect()->route('setup.complete');
    }

    public function postAccount(ProfileRequest $request)
    {
        $role = Role::findByName('superadmin');
        if (!User::count()) {
            $input = request()->all();

            unset($input['user']['password_confirmation']);

            $user = new User($input['user']);

            $user->password = $input['user']['password'];

            $user->save();
            $user->assignRole('superadmin');

            $companyProfile = CompanyProfile::create($input['company_profile']);

            Setting::saveByKey('defaultCompanyProfile', $companyProfile->id);
        } elseif (!$role->users()->count()) {
            $superadmin = $request->role1; //superadmin
            $user = User::find($superadmin);
            $user->assignRole('superadmin');

            $others = $request->role2; //admin or user
            if ($others) {
                foreach ($others as $id => $role) {
                    $user = User::find($id);
                    $user->assignRole(Str::before($role, '_'));
                }
            }
        }

        return redirect()->route('setup.complete');
    }

    public function complete()
    {
        return view('setup.complete');
    }


}
