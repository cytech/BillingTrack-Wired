<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Vendors\Controllers\ContactController;
use BT\Modules\Vendors\Controllers\VendorController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('vendors')->name('vendors.')->group(function () {
        Route::name('index')->get('/', [VendorController::class, 'index']);
        Route::name('create')->get('create', [VendorController::class, 'create']);
        Route::name('store')->post('create', [VendorController::class, 'store']);
        Route::name('show')->get('{id}', [VendorController::class, 'show']);
        Route::name('edit')->get('{id}/edit', [VendorController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [VendorController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [VendorController::class, 'delete']);
        Route::name('ajax.lookup')->get('ajax/lookup', [VendorController::class, 'ajaxLookup']);
        Route::name('ajax.modalEdit')->post('ajax/modal_edit', [VendorController::class, 'ajaxModalEdit']);
        Route::name('ajax.modalUpdate')->post('ajax/modal_update/{id}', [VendorController::class, 'ajaxModalUpdate']);

        Route::prefix('{vendorId}/contacts')->group(function () {
            Route::name('contacts.create')->get('create', [ContactController::class, 'create']);
            Route::name('contacts.store')->post('create', [ContactController::class, 'store']);
            Route::name('contacts.edit')->get('edit/{contactId}', [ContactController::class, 'edit']);
            Route::name('contacts.update')->post('edit/{contactId}', [ContactController::class, 'update']);
            Route::name('contacts.delete')->post('delete', [ContactController::class, 'delete']);
            Route::name('contacts.updateDefault')->post('default', [ContactController::class, 'updateDefault']);
        });
    });

