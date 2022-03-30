<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Setup\Controllers\SetupController;

Route::middleware('web')
    ->prefix('setup')->name('setup.')->group(function () {
        Route::name('index')->get('/', [SetupController::class, 'index']);
        Route::name('postIndex')->post('/', [SetupController::class, 'postIndex']);
        Route::name('prerequisites')->get('prerequisites', [SetupController::class, 'prerequisites']);
        Route::name('migration')->get('migration', [SetupController::class, 'migration']);
        Route::name('postMigration')->post('migration', [SetupController::class, 'postMigration']);
        Route::name('account')->get('account', [SetupController::class, 'account']);
        Route::name('postAccount')->post('account', [SetupController::class, 'postAccount']);
        Route::name('complete')->get('complete', [SetupController::class, 'complete']);
    });
