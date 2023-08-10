<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version520 extends Migration
{
    /**
     * Run the migrations.
     *
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        //fullcalendar changed themeing, removed jqueryui
        $themekey = Setting::getByKey('schedulerFcThemeSystem');
        if ($themekey == 'bootstrap4') {
            Setting::saveByKey('schedulerFcThemeSystem', 'bootstrap');
        }
        if ($themekey == 'jquery-ui') {
            Setting::saveByKey('schedulerFcThemeSystem', 'standard');
        }
        Setting::saveByKey('version', '5.2.0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
