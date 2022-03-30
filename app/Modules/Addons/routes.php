<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Addons\Controllers\AddonController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('addons')->name('addons.')->group(function () {
        Route::name('index')->get('/', [AddonController::class, 'index']);
        Route::name('install')->get('install/{id}', [AddonController::class, 'install']);
        Route::name('uninstall')->get('uninstall/{id}', [AddonController::class, 'uninstall']);
        Route::name('upgrade')->get('upgrade/{id}', [AddonController::class, 'upgrade']);
    });
