<?php

namespace Database\Seeders;

use BT\Modules\Currencies\Models\Currency;
use Eloquent;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Currency::exists()) {
            return;
        }

        Eloquent::unguard();

        Currency::create(['id' => 1, 'code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
        Currency::create(['id' => 2, 'code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
        Currency::create(['id' => 3, 'code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
        Currency::create(['id' => 4, 'code' => 'GBP', 'name' => 'Pound Sterling', 'symbol' => '£', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
        Currency::create(['id' => 5, 'code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);

        Eloquent::reguard();
    }
}
