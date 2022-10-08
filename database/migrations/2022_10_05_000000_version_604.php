<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version604 extends Migration
{

    /**
     * Run the migrations.
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        Setting::saveByKey('version', '6.0.4');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Setting::saveByKey('version', '6.0.3');
     }
}
