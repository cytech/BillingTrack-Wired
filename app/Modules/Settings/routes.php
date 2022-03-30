<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Settings\Controllers\SettingController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('settings')->name('settings.')->group(function () {
        Route::name('index')->get('/', [SettingController::class, 'index']);
        Route::name('update')->post('/', [SettingController::class, 'update']);
        Route::name('updateCheck')->get('update_check', [SettingController::class, 'updateCheck']);
        Route::name('logo.delete')->get('logo/delete', [SettingController::class, 'logoDelete']);
        Route::name('saveTab')->post('save_tab', [SettingController::class, 'saveTab']);
    });
