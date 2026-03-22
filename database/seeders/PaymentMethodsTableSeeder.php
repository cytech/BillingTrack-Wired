<?php

namespace Database\Seeders;

use BT\Modules\PaymentMethods\Models\PaymentMethod;
use Eloquent;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (PaymentMethod::exists()) {
            return;
        }

        Eloquent::unguard();

        PaymentMethod::create(['id' => 1, 'name' => 'Cash']);
        PaymentMethod::create(['id' => 2, 'name' => 'Check']);
        PaymentMethod::create(['id' => 3, 'name' => 'Credit Card']);
        PaymentMethod::create(['id' => 4, 'name' => 'Online Payment']);

        Eloquent::reguard();
    }
}
