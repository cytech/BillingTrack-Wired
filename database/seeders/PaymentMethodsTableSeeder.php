<?php

namespace Database\Seeders;

use BT\Modules\PaymentMethods\Models\PaymentMethod;
use DB;
use Eloquent;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        if (count(PaymentMethod::all())){ return; }
        if (PaymentMethod::exists()) {
            return;
        }

        Eloquent::unguard();

        //        DB::table('payment_methods')->insert(['id' => 1,'name' => 'Cash']);
        //        DB::table('payment_methods')->insert(['id' => 2,'name' => 'Check']);
        //        DB::table('payment_methods')->insert(['id' => 3,'name' => 'Credit Card']);
        //        DB::table('payment_methods')->insert(['id' => 4,'name' => 'Online Payment']);

        PaymentMethod::create(['id' => 1, 'name' => 'Cash']);
        PaymentMethod::create(['id' => 2, 'name' => 'Check']);
        PaymentMethod::create(['id' => 3, 'name' => 'Credit Card']);
        PaymentMethod::create(['id' => 4, 'name' => 'Online Payment']);

        Eloquent::reguard();
    }
}
