<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version610 extends Migration
{

    /**
     * Run the migrations.
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        Setting::saveByKey('skin','{"headBackground":"purple","headClass":"light","sidebarMode":"open"}');
        Setting::saveByKey('version', '6.1.0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Setting::saveByKey('version', '6.0.6');
     }
}
