<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\RecurringInvoices\Controllers\RecurringInvoiceController;
use BT\Modules\RecurringInvoices\Controllers\RecurringInvoiceEditController;
use BT\Modules\RecurringInvoices\Controllers\RecurringInvoiceItemController;
use BT\Modules\RecurringInvoices\Controllers\RecurringInvoiceRecalculateController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('recurring_invoices')->name('recurringInvoices.')->group(function () {
        Route::name('index')->get('/', [RecurringInvoiceController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [RecurringInvoiceController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [RecurringInvoiceController::class, 'bulkDelete']);

        Route::name('edit')->get('{id}/edit', [RecurringInvoiceEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [RecurringInvoiceEditController::class, 'update']);
        Route::name('recurringInvoiceEdit.refreshEdit')->get('{id}/edit/refresh', [RecurringInvoiceEditController::class, 'refreshEdit']);
        Route::name('recurringInvoiceEdit.refreshTotals')->post('edit/refresh_totals', [RecurringInvoiceEditController::class, 'refreshTotals']);
        Route::name('recurringInvoiceEdit.refreshTo')->post('edit/refresh_to', [RecurringInvoiceEditController::class, 'refreshTo']);
        Route::name('recurringInvoiceEdit.refreshFrom')->post('edit/refresh_from', [RecurringInvoiceEditController::class, 'refreshFrom']);
        //Route::name('recurringInvoiceEdit.updateClient')->post('edit/update_client', [RecurringInvoiceEditController::class, 'updateClient']);
        Route::name('recurringInvoiceEdit.updateCompanyProfile')->post('edit/update_company_profile', [RecurringInvoiceEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [RecurringInvoiceRecalculateController::class, 'recalculate']);
    });

    Route::prefix('recurring_invoice_item')->name('recurringInvoiceItem.')->group(function () {
        Route::name('delete')->post('delete', [RecurringInvoiceItemController::class, 'delete']);
    });
});
