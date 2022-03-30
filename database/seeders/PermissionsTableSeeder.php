<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Static_;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    public static $modules = [
        'Client',
        'Quote',
        'Workorder',
        'Invoice',
        'RecurringInvoice',
        'Payment',
        'Expense',
        'TimeTrackingProject',
        'Purchaseorder',
        'Schedule',
    ];

    public static $reports = [
        'ClientStatementReport',
        'ExpenseListReport',
        'ItemSalesReport',
        'PaymentsCollectedReport',
        'ProfitLossReport',
        'RevenueByClientReport',
        'TaxSummaryReport',
        'TimeSheetReport',
        'TimeTrackingReport',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $perms = [
            ['name' => 'view_roles', 'description' => 'View Roles', 'group' => 'ACL.Roles', 'guard_name' => 'web'],
            ['name' => 'create_roles', 'description' => 'Create Roles', 'group' => 'ACL.Roles', 'guard_name' => 'web'],
            ['name' => 'edit_roles', 'description' => 'Edit Roles', 'group' => 'ACL.Roles', 'guard_name' => 'web'],
            ['name' => 'delete_roles', 'description' => 'Delete Roles', 'group' => 'ACL.Roles', 'guard_name' => 'web'],
            ['name' => 'view_permissions', 'description' => 'View Permissions', 'group' => 'ACL.Permissions', 'guard_name' => 'web'],
            ['name' => 'create_permissions', 'description' => 'Create Permissions', 'group' => 'ACL.Permissions', 'guard_name' => 'web'],
            ['name' => 'edit_permissions', 'description' => 'Edit Permissions', 'group' => 'ACL.Permissions', 'guard_name' => 'web'],
            ['name' => 'delete_permissions', 'description' => 'Delete Permissions', 'group' => 'ACL.Permissions', 'guard_name' => 'web'],
            ['name' => 'view_users', 'description' => 'View Users', 'group' => 'ACL.Users', 'guard_name' => 'web'],
            ['name' => 'create_users', 'description' => 'Create Users', 'group' => 'ACL.Users', 'guard_name' => 'web'],
            ['name' => 'edit_users', 'description' => 'Edit Users', 'group' => 'ACL.Users', 'guard_name' => 'web'],
            ['name' => 'delete_users', 'description' => 'Delete Users', 'group' => 'ACL.Users', 'guard_name' => 'web']
        ];

        foreach (static::$modules as $module) {
            //$perms += ['name' => 'view_' . $module, 'description' => 'View ' . $module . ' Information', 'guard_name' => 'web'];
            $perms[] = ['name' => 'view_' . $module, 'description' => 'View ' . $module . ' Information', 'group' => 'Modules.' . $module, 'guard_name' => 'web'];
            $perms[] = ['name' => 'create_' . $module, 'description' => 'Create a new ' . $module, 'group' => 'Modules.' . $module, 'guard_name' => 'web'];
            $perms[] = ['name' => 'edit_' . $module, 'description' => 'Edit ' . $module . ' Information', 'group' => 'Modules.' . $module, 'guard_name' => 'web'];
            $perms[] = ['name' => 'delete_' . $module, 'description' => 'Delete a ' . $module, 'group' => 'Modules.' . $module, 'guard_name' => 'web'];
        }

        foreach (static::$reports as $report) {
            $perms[] = ['name' => 'view_' . $report, 'description' => 'View ' . $report , 'group' => 'Reports', 'guard_name' => 'web'];
        }

        foreach ($perms as $perm) {
            $permission = new Permission();
            $permission->name = $perm['name'];
            $permission->description = $perm['description'];
            $permission->group = $perm['group'];
            $permission->guard_name = $perm['guard_name'];
            $permission->save();
        }

    }
}
