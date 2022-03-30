<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Invoices\Controllers\InvoiceController;
use BT\Modules\Invoices\Controllers\InvoiceEditController;
use BT\Modules\Invoices\Controllers\InvoiceItemController;
use BT\Modules\Invoices\Controllers\InvoiceMailController;
use BT\Modules\Invoices\Controllers\InvoiceRecalculateController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::name('index')->get('/', [InvoiceController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [InvoiceController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [InvoiceController::class, 'bulkDelete']);
        Route::name('bulk.status')->post('bulk/status', [InvoiceController::class, 'bulkStatus']);
        Route::name('pdf')->get('{id}/pdf', [InvoiceController::class, 'pdf']);

        Route::name('edit')->get('{id}/edit', [InvoiceEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [InvoiceEditController::class, 'update']);
        Route::name('invoiceEdit.refreshEdit')->get('{id}/edit/refresh', [InvoiceEditController::class, 'refreshEdit']);
        Route::name('invoiceEdit.refreshTotals')->post('edit/refresh_totals', [InvoiceEditController::class, 'refreshTotals']);
        Route::name('invoiceEdit.refreshTo')->any('edit/refresh_to', [InvoiceEditController::class, 'refreshTo']);
        Route::name('invoiceEdit.refreshFrom')->post('edit/refresh_from', [InvoiceEditController::class, 'refreshFrom']);
        //Route::name('invoiceEdit.updateClient')->post('edit/update_client', [InvoiceEditController::class, 'updateClient']);
        Route::name('invoiceEdit.updateCompanyProfile')->post('edit/update_company_profile', [InvoiceEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [InvoiceRecalculateController::class, 'recalculate']);
    });

    Route::prefix('invoice_mail')->name('invoiceMail.')->group(function () {
        Route::name('create')->post('create', [InvoiceMailController::class, 'create']);
        Route::name('store')->post('store', [InvoiceMailController::class, 'store']);
    });

    Route::prefix('invoice_item')->name('invoiceItem.')->group(function () {
        Route::name('delete')->post('delete', [InvoiceItemController::class, 'delete']);
    });
});
