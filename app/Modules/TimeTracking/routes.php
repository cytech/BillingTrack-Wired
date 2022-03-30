<?php

use BT\Modules\TimeTracking\Controllers\ProjectController;
use BT\Modules\TimeTracking\Controllers\TaskBillController;
use BT\Modules\TimeTracking\Controllers\TaskController;
use BT\Modules\TimeTracking\Controllers\TimerController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('time_tracking')->name('timeTracking.')->group(function () {
        Route::prefix('projects')->group(function () {
            Route::name('projects.index')->get('/', [ProjectController::class, 'index']);
            Route::name('projects.create')->get('create', [ProjectController::class, 'create']);
            Route::name('projects.store')->post('create', [ProjectController::class, 'store']);
            Route::name('projects.edit')->get('{id}/edit', [ProjectController::class, 'edit']);
            Route::name('projects.update')->post('{id}/edit', [ProjectController::class, 'update']);
            Route::name('projects.delete')->get('{id}/delete', [ProjectController::class, 'delete']);
            Route::name('projects.refreshTaskList')->post('refresh_task_list', [ProjectController::class, 'refreshTaskList']);
            Route::name('projects.refreshTotals')->post('refresh_totals', [ProjectController::class, 'refreshTotals']);
            Route::name('projects.bulk.delete')->post('bulk/delete', [ProjectController::class, 'bulkDelete']);
            Route::name('projects.bulk.status')->post('bulk/status', [ProjectController::class, 'bulkStatus']);
        });

        Route::prefix('tasks')->group(function () {
            Route::name('tasks.create')->post('create', [TaskController::class, 'create']);
            Route::name('tasks.store')->post('store', [TaskController::class, 'store']);
            Route::name('tasks.edit')->post('edit', [TaskController::class, 'edit']);
            Route::name('tasks.update')->post('update', [TaskController::class, 'update']);
            Route::name('tasks.delete')->post('delete', [TaskController::class, 'delete']);
            Route::name('tasks.updateDisplayOrder')->post('update_display_order', [TaskController::class, 'updateDisplayOrder']);
        });

        Route::prefix('timers')->group(function () {
            Route::name('timers.start')->post('start', [TimerController::class, 'start']);
            Route::name('timers.stop')->post('stop', [TimerController::class, 'stop']);
            Route::name('timers.show')->post('show', [TimerController::class, 'show']);
            Route::name('timers.store')->post('store', [TimerController::class, 'store']);
            Route::name('timers.delete')->post('delete', [TimerController::class, 'delete']);
            Route::name('timers.seconds')->post('seconds', [TimerController::class, 'seconds']);
            Route::name('timers.refreshList')->post('refresh_list', [TimerController::class, 'refreshList']);
        });

        Route::prefix('bill')->group(function () {
            Route::name('bill.create')->post('create', [TaskBillController::class, 'create']);
            Route::name('bill.store')->post('store', [TaskBillController::class, 'store']);
        });

    });
