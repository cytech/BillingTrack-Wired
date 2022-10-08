<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Clients\Controllers\ClientController;
use BT\Modules\Clients\Controllers\ContactController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('clients')->name('clients.')->group(function () {
        Route::name('index')->get('/', [ClientController::class, 'index']);
        Route::name('create')->get('create', [ClientController::class, 'create']);
        Route::name('store')->post('create', [ClientController::class, 'store']);
        Route::name('show')->get('{id}', [ClientController::class, 'show']);
        Route::name('edit')->get('{id}/edit', [ClientController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [ClientController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [ClientController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [ClientController::class, 'bulkDelete']);
        Route::name('ajax.modalEdit')->post('ajax/modal_edit', [ClientController::class, 'ajaxModalEdit']);
        Route::name('ajax.modalUpdate')->post('ajax/modal_update/{id}', [ClientController::class, 'ajaxModalUpdate']);
        Route::name('ajax.checkDuplicateName')->post('ajax/check_duplicate_name', [ClientController::class, 'ajaxCheckDuplicateName']);
        Route::name('saveTab')->post('save_tab', [ClientController::class, 'saveTab']);

        Route::group(['prefix' => '{clientId}/contacts'], function () {
            Route::name('contacts.create')->get('create', [ContactController::class, 'create']);
            Route::name('contacts.store')->post('create', [ContactController::class, 'store']);
            Route::name('contacts.edit')->get('edit/{contactId}', [ContactController::class, 'edit']);
            Route::name('contacts.update')->post('edit/{contactId}', [ContactController::class, 'update']);
            Route::name('contacts.delete')->post('delete', [ContactController::class, 'delete']);
            Route::name('contacts.updateDefault')->post('default', [ContactController::class, 'updateDefault']);
        });
    });
