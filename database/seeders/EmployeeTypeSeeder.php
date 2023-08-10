<?php

namespace Database\Seeders;

use BT\Modules\Employees\Models\EmployeeType;
use Eloquent;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    public function run()
    {
        if (EmployeeType::exists()) {
            return;
        }

        Eloquent::unguard();

        $types = [
            ['name' => ''],
            ['name' => 'Full-Time'],
            ['name' => 'Part-Time'],
            ['name' => 'Temporary'],
            ['name' => 'Seasonal'],
            ['name' => 'Leased'],
            ['name' => 'Contractor'],
            ['name' => 'Freelancer'],
            ['name' => 'Consultant'],
            ['name' => 'Intern'],
            ['name' => 'Other'],
        ];

        foreach ($types as $type) {
            $record = EmployeeType::whereName($type['name'])->first();
            if (! $record) {
                EmployeeType::create($type);
            }
        }

        Eloquent::reguard();
    }
}
