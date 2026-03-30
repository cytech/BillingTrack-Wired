<?php

namespace Database\Factories;

use BT\Modules\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'number' => fake()->unique()->randomNumber(3),
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'full_name' => null,
            'short_name' => null,
            'title' => 'Worker',
            'type_id' => fake()->numberBetween(1, 11),
            'billing_rate' => '20.00',
            'schedule' => '1',
            'active' => '1',
            'driver' => fake()->numberBetween(0, 1),
        ];
    }
}
