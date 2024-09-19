<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Version703 extends Migration
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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('summary', 255)->nullable()->default(null)->change();
        });

        Setting::saveByKey('version', '7.0.3');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::saveByKey('version', '7.0.2');
    }
}
