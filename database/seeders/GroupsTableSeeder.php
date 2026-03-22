<?php

namespace Database\Seeders;

use BT\Modules\Groups\Models\Group;
use Eloquent;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (Group::exists()) {
            return;
        }

        Eloquent::unguard();

        Group::create(['id' => 1, 'name' => 'Invoice Default', 'next_id' => 1, 'left_pad' => 0, 'format' => 'INV{NUMBER}', 'last_id' => 0, 'last_year' => 0, 'last_month' => 0, 'last_week' => 0, 'last_number' => 0]);
        Group::create(['id' => 2, 'name' => 'Quote Default', 'next_id' => 1, 'left_pad' => 0, 'format' => 'QUO{NUMBER}', 'last_id' => 0, 'last_year' => 0, 'last_month' => 0, 'last_week' => 0, 'last_number' => 0]);
        Group::create(['id' => 3, 'name' => 'Workorder Default', 'next_id' => 1, 'left_pad' => 0, 'format' => 'WO{NUMBER}', 'last_id' => 0, 'last_year' => 0, 'last_month' => 0, 'last_week' => 0, 'last_number' => 0]);

        Eloquent::reguard();
    }
}
