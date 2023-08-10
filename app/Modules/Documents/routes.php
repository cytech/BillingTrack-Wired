<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Documents\Controllers\DocumentController;
use BT\Modules\Documents\Controllers\DocumentEditController;
use BT\Modules\Documents\Controllers\DocumentItemController;
use BT\Modules\Documents\Controllers\DocumentMailController;
use BT\Modules\Documents\Controllers\DocumentRecalculateController;
use BT\Modules\Documents\Controllers\DocumentToInvoiceController;
use BT\Modules\Documents\Controllers\DocumentToWorkorderController;
use BT\Modules\Employees\Controllers\EmployeeController;
use BT\Modules\Products\Controllers\ProductController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::name('index')->get('/', [DocumentController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [DocumentController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [DocumentController::class, 'bulkDelete']);
        Route::name('bulk.status')->post('bulk/status', [DocumentController::class, 'bulkStatus']);
        Route::name('pdf')->get('{id}/pdf', [DocumentController::class, 'pdf']);
        Route::name('receive')->post('receive', [DocumentController::class, 'receive']);
        Route::name('receive_items')->post('receive_items', [DocumentController::class, 'receiveItems']);

        Route::name('edit')->get('{id}/edit', [DocumentEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [DocumentEditController::class, 'update']);
        Route::name('documentEdit.refreshEdit')->get('{id}/edit/refresh', [DocumentEditController::class, 'refreshEdit']);
        Route::name('documentEdit.refreshTotals')->post('edit/refresh_totals', [DocumentEditController::class, 'refreshTotals']);
        Route::name('documentEdit.refreshTo')->post('edit/refresh_to', [DocumentEditController::class, 'refreshTo']);
        Route::name('documentEdit.refreshFrom')->post('edit/refresh_from', [DocumentEditController::class, 'refreshFrom']);
        Route::name('documentEdit.updateCompanyProfile')->post('edit/update_company_profile', [DocumentEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [DocumentRecalculateController::class, 'recalculate']);
    });

    Route::prefix('document_to_invoice')->name('documentToInvoice.')->group(function () {
        Route::name('create')->post('create', [DocumentToInvoiceController::class, 'create']);
        Route::name('store')->post('store', [DocumentToInvoiceController::class, 'store']);
    });

    Route::prefix('document_to_workorder')->name('documentToWorkorder.')->group(function () {
        Route::name('create')->post('create', [DocumentToWorkorderController::class, 'create']);
        Route::name('store')->post('store', [DocumentToWorkorderController::class, 'store']);
    });

    Route::prefix('document_mail')->name('documentMail.')->group(function () {
        Route::name('create')->post('create', [DocumentMailController::class, 'create']);
        Route::name('store')->post('store', [DocumentMailController::class, 'store']);
    });

    Route::prefix('document_item')->name('documentItem.')->group(function () {
        Route::name('delete')->post('delete', [DocumentItemController::class, 'delete']);
    });
});
Route::middleware(['web', 'auth.admin'])->group(function () {
    //resource and employee force update
    Route::get('/forceProductUpdate/{ret}', [ProductController::class, 'forceLUTupdate']);
    Route::get('/forceEmployeeUpdate/{ret}', [EmployeeController::class, 'forceLUTupdate']);
});
