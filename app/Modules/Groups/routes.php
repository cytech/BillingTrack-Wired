<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Groups\Controllers\GroupController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('groups')->name('groups.')->group(function () {
        Route::name('index')->get('/', [GroupController::class, 'index']);
        Route::name('create')->get('create', [GroupController::class, 'create']);
        Route::name('store')->post('groups', [GroupController::class, 'store']);
        Route::name('edit')->get('{group}/edit', [GroupController::class, 'edit']);
        Route::name('update')->post('{group}', [GroupController::class, 'update']);
        Route::name('delete')->get('{group}/delete', [GroupController::class, 'delete']);

    });
