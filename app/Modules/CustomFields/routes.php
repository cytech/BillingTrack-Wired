<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\CustomFields\Controllers\CustomFieldController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('custom_fields')->name('customFields.')->group(function () {
        Route::name('index')->get('/', [CustomFieldController::class, 'index']);
        Route::name('create')->get('create', [CustomFieldController::class, 'create']);
        Route::name('store')->post('custom_fields', [CustomFieldController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [CustomFieldController::class, 'edit']);
        Route::name('update')->post('{id}', [CustomFieldController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [CustomFieldController::class, 'delete']);
    });
