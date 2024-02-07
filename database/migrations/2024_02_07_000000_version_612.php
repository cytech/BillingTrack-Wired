<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version612 extends Migration
{

    /**
     * Run the migrations.
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        $skinvalue = Setting::getByKey('skin');
        $skinvalue = str_replace('mini', 'collapse', $skinvalue);
        Setting::saveByKey('skin', $skinvalue);

        Setting::saveByKey('version', '6.1.2');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Setting::saveByKey('version', '6.1.1');
     }
}
