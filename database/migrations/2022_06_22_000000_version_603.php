<?php

use BT\Modules\Settings\Models\Setting;
use Database\Seeders\EmployeeTypeSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Version603 extends Migration
{

    /**
     * Run the migrations.
     * @table employee_types
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_types' , function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 85)->nullable()->default(null);
        });

        //seed employee_types
        Artisan::call('db:seed', [
            '--class' => EmployeeTypeSeeder::class
        ]);

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedTinyInteger('type_id')->after('title')->nullable()->default(null);
            $table->date('term_date')->after('type_id')->nullable()->default(null);
        });

        Setting::saveByKey('version', '6.0.3');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Schema::dropIfExists('employee_types');

         Schema::table('employees', function (Blueprint $table) {
             $table->dropColumn(['type_id', 'term_date']);
         });

         Setting::saveByKey('version', '6.0.2');
     }
}
