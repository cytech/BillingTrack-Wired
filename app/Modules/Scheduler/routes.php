<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use BT\Modules\Scheduler\Controllers\SchedulerCategoryController;
use BT\Modules\Scheduler\Controllers\SchedulerController;
use BT\Modules\Scheduler\Controllers\SchedulerEditController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('scheduler')->name('scheduler.')->group(function () {
        Route::name('index')->get('/', [SchedulerController::class, 'index']);
        Route::name('fullcalendar')->get('fullcalendar', [SchedulerController::class, 'calendar']);
        Route::name('showschedule')->get('showschedule', [SchedulerController::class, 'showSchedule']);
        Route::name('showschedule')->post('showschedule', [SchedulerController::class, 'showSchedule']);
        Route::name('tableevent')->get('table_event', [SchedulerController::class, 'tableEvent']);
        Route::name('tablerecurringevent')->get('table_recurring_event', [SchedulerController::class, 'tableRecurringEvent']);
        //route to pass available resources to ajax in _js_calendar.blade
        Route::name('getresources')->get('getResources/{date}', [SchedulerController::class, 'scheduledResources']);
        //trash
        Route::name('trashevent')->get('trash_event/{id}', [SchedulerController::class, 'trashEvent']);
        Route::name('trashreminder')->get('trash_reminder/{id}', [SchedulerController::class, 'trashReminder']);
        Route::name('bulk.delete')->post('bulk/delete', [SchedulerController::class, 'bulkDelete']);
        //utilities
        Route::name('checkschedule')->get('checkschedule', [SchedulerController::class, 'checkSchedule']);
        Route::name('getreplace.employee')->get('getreplaceemployee/{item_id}/{name}/{date}', [SchedulerController::class, 'getReplaceEmployee']);
        Route::name('setreplace.employee')->post('setreplaceemployee', [SchedulerController::class, 'setReplaceEmployee']);

        //edit
        Route::name('editrecurringevent')->get('table_recurring_event/edit_recurring_event/{id?}', [SchedulerEditController::class, 'editRecurringEvent']);
        Route::name('updaterecurringevent')->any('table_recurring_event/update_recurring_event/{id?}', [SchedulerEditController::class, 'updateRecurringEvent']);
        //route for ajax calc of human readable recurrence frequency
        Route::name('gethuman')->post('get_human', [SchedulerEditController::class, 'getHuman']);
        //categories
        Route::name('categories.index')->get('categories', [SchedulerCategoryController::class, 'index']);
        Route::name('categories.create')->get('categories/create', [SchedulerCategoryController::class, 'create']);
        Route::name('categories.store')->post('categories/store', [SchedulerCategoryController::class, 'store']);
        Route::name('categories.show')->get('categories/{id}', [SchedulerCategoryController::class, 'show']);
        Route::name('categories.edit')->get('categories/{id}/edit', [SchedulerCategoryController::class, 'edit']);
        Route::name('categories.update')->put('categories/{id}', [SchedulerCategoryController::class, 'update']);
        Route::name('categories.delete')->get('categories/delete/{id}', [SchedulerCategoryController::class, 'delete']);
    });

});
