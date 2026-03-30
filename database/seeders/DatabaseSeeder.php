<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            CurrenciesTableSeeder::class,
            EmployeeTypeSeeder::class,
            GroupsTableSeeder::class,
            IndustrySeeder::class,
            InventoryTypesSeeder::class,
            PaymentMethodsTableSeeder::class,
            PaymentTermsSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            SettingsTableSeeder::class,
            SizeSeeder::class,
            TitleSeeder::class,
        ]);
    }
}
