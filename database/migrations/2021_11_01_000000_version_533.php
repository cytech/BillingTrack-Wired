<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version533 extends Migration
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
        Setting::saveByKey('version', '5.3.3');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::saveByKey('version', '5.3.2');
    }
}
