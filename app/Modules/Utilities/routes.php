<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Utilities\Controllers\BackupController;
use BT\Modules\Utilities\Controllers\UtilityController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('utilities')->name('utilities.')->group(function () {
        Route::name('manage_trash')->get('manage_trash', [UtilityController::class, 'manageTrash']);
        Route::name('restore_trash')->get('{id}/restore_trash/{entity}', [UtilityController::class, 'restoreTrash']);
        Route::name('delete_trash')->get('{id}/delete_trash/{entity}', [UtilityController::class, 'deleteTrash']);
        Route::name('bulk.deletetrash')->post('bulk/delete_trash', [UtilityController::class, 'bulkDeleteTrash']);
        Route::name('bulk.restoretrash')->post('bulk/restore_trash', [UtilityController::class, 'bulkRestoreTrash']);
        Route::name('batchprint')->any('batchprint/{module?}', [UtilityController::class, 'batchPrint']);
        Route::name('saveTab')->post('save_tab', [UtilityController::class, 'saveTab']);

        if (!config('app.demo')) {
            Route::name('database')->get('database', [BackupController::class, 'index']);
            Route::name('backup.database')->get('backup/database', [BackupController::class, 'database']);
            Route::name('trashprior.database')->get('trashprior/database', [BackupController::class, 'trashPrior']);
            Route::name('deleteprior.database')->get('deleteprior/database', [BackupController::class, 'deletePrior']);
            Route::name('clientprior.database')->get('clientprior/database', [BackupController::class, 'clientInactivePrior']);
        }

    });
