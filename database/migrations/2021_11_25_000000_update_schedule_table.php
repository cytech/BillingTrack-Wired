<?php

use BT\Modules\Scheduler\Models\Schedule;
use BT\Modules\Scheduler\Models\ScheduleReminderLegacy;
use BT\Modules\Scheduler\Models\ScheduleResource;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        // changing resource relation to occurrence instead of schedule
        // remap resource to latest occurrence from schedule
        $resources = ScheduleResource::all();
        foreach ($resources as $resource){
            $sched = Schedule::find($resource->schedule_id);
            $resource->schedule_id = $sched->latestOccurrence->oid;
            $resource->save();
        }

        Schema::table('schedule', function (Blueprint $table) {
            $table->string('location_str', 255)->after('title')->nullable()->default(null);
        });

        Schema::table('schedule_occurrences', function (Blueprint $table) {
            $table->integer('reminder_qty')->unsigned()->after('end_date')->nullable()->default(0);
            $table->string('reminder_interval', 45)->after('reminder_qty')->nullable()->default('none');
            $table->dateTime('reminder_date')->after('reminder_interval')->nullable()->default(null);
            $table->renameColumn('oid', 'id');
        });

        $oldreminders = ScheduleReminderLegacy::all();

        foreach ($oldreminders as $movereminder){
            $movereminder->schedule->location_str = $movereminder->reminder_location;
            $movereminder->schedule->occurrence->reminder_date = $movereminder->reminder_date;
            $movereminder->schedule->save();
            $movereminder->schedule->occurrence->save();
        }

        Schema::table('schedule_resources', function (Blueprint $table) {
            $table->dropForeign('schedule_resource_schedule_id_foreign');
            $table->renameColumn('schedule_id', 'occurrence_id');

            $table->foreign('occurrence_id', 'schedule_resource_occurrence_id_foreign')
                ->references('id')->on('schedule_occurrences')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::dropIfExists('schedule_reminders');
        // url column not used
        Schema::table('schedule', function (Blueprint $table) {
            $table->dropColumn('url');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         //no going back...
//         Schema::table('schedule', function (Blueprint $table) {
//             $table->dropColumn('location_str');
//         });
//
//         Schema::table('schedule_occurences', function (Blueprint $table) {
//             $table->dropColumn('reminder_qty');
//             $table->dropColumn('reminder_interval');
//             $table->dropColumn('reminder_date');
//         });
     }
}
