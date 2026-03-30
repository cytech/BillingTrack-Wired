<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Database\Factories;

use BT\Http\Controllers\Controller;
use BT\Modules\Clients\Models\Client;
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Products\Models\Product;
use BT\Modules\Vendors\Models\Vendor;

/*
 *  run in tinker - includes client/vendor contact seeding
 *  Client::factory(25)->create()
 *  run Client twice - comment/uncomment return $company; return $individual; in ClientFactory.php between runs
 *  Product::factory(20)->create()
 *  Employee::factory(10)->create()
 *  Vendor::factory(10)->create()
 * */

class SeedDemoController extends Controller
{
    public function seedDemo()
    {
        $clients = Client::factory()->count(25)->create();
        $products = Product::factory()->count(20)->create();
        $employees = Employee::factory()->count(10)->create();
        $vendors = Vendor::factory()->count(10)->create();
    }

}
