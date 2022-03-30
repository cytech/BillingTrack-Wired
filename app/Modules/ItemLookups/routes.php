<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\ItemLookups\Controllers\ItemLookupController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('item_lookups')->name('itemLookups.')->group(function () {
        Route::name('index')->get('/', [ItemLookupController::class, 'index']);
        Route::name('create')->get('create', [ItemLookupController::class, 'create']);
        Route::name('store')->post('item_lookups', [ItemLookupController::class, 'store']);
        Route::name('edit')->get('{itemLookup}/edit', [ItemLookupController::class, 'edit']);
        Route::name('update')->post('{itemLookup}', [ItemLookupController::class, 'update']);
        Route::name('delete')->get('{itemLookup}/delete', [ItemLookupController::class, 'delete']);
    });
