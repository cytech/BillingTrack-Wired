<?php

use BT\Modules\Clients\Models\Client;
use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Version600 extends Migration
{

    /**
     * Run the migrations.
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorders', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned()->default('0')->change();
        });

        $clients = Client::withTrashed()->get(['id', 'name', 'unique_name']);

        foreach ($clients as $client) {
            //if client->name and $client->unique_name are the same, truncate name and add underscore and random 5 character suffix
            if ($client->name == $client->unique_name) {
                $newunique = substr(str_replace('_', '-', $client->name), 0, 10) . '_' .
                    substr(base_convert(mt_rand(), 10, 36), 0, 5);
            } //if $client->unique_name contains $client_name, truncate name and add underscore and add unique_name with client-> name stripped
            elseif (strpos($client->unique_name, $client->name) !== false) {
                $newunique = substr(str_replace('_', '-', $client->name), 0, 10) . '_' .
                    str_replace($client->name, '', $client->unique_name);
            } // if unique_name does not fit above, append to client->name as prefix
            else {
                $newunique = substr(str_replace('_', '-', $client->name), 0, 10) . '_' .
                    $client->unique_name;
            }

            $client->updateQuietly(['unique_name' => $newunique]);
        }

        Setting::deleteByKey('jquiTheme');

        //fullcalendar changed themeing, added bootstrap5, cytech removed bootstrap4
        $themekey = Setting::getByKey('schedulerFcThemeSystem');
        if ($themekey == 'bootstrap'){
            Setting::saveByKey('schedulerFcThemeSystem', 'bootstrap5');
        }

        Setting::saveByKey('version', '6.0.0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Schema::table('quotes', function (Blueprint $table) {
             $table->integer('invoice_id')->default('0')->change();
         });

         Setting::saveByKey('version', '5.3.2');
     }
}
