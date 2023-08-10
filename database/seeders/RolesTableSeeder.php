<?php

namespace Database\Seeders;

use Eloquent;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (Role::exists()) {
            return;
        }

        Eloquent::unguard();

        $roles = [
            ['name' => 'superadmin', 'description' => 'TOTAL control Role', 'guard_name' => 'web'],
            ['name' => 'admin', 'description' => 'Administrator role', 'guard_name' => 'web'],
            ['name' => 'user', 'description' => 'Standard User role', 'guard_name' => 'web'],
            ['name' => 'client', 'description' => 'Client role', 'guard_name' => 'web'],
        ];

        foreach ($roles as $r) {
            $role = new Role();
            $role->name = $r['name'];
            $role->description = $r['description'];
            $role->guard_name = $r['guard_name'];
            $role->save();

            // attach all permissions to superadmin   1-12
            if ($role->name == 'superadmin') {
                $role->syncPermissions(Permission::all());
            }// attach permissions to admin   notin 1,2,4,5,6,8
            elseif ($role->name == 'admin') {
                $role->syncPermissions(Permission::whereNotIn('id', [2, 3, 4, 6, 7, 8])->get());
            } // attach permissions to user  0
            elseif ($role->name == 'user') {
                $role->syncPermissions(Permission::whereNotIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16])->get());
            } // attach permissions to client 0
            else {
                //$role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
            }
        }
        Eloquent::reguard();
    }
}
