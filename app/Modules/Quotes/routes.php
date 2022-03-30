<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Quotes\Controllers\QuoteController;
use BT\Modules\Quotes\Controllers\QuoteEditController;
use BT\Modules\Invoices\Controllers\InvoiceEditController;
use BT\Modules\Quotes\Controllers\QuoteItemController;
use BT\Modules\Quotes\Controllers\QuoteMailController;
use BT\Modules\Quotes\Controllers\QuoteRecalculateController;
use BT\Modules\Quotes\Controllers\QuoteToInvoiceController;
use BT\Modules\Quotes\Controllers\QuoteToWorkorderController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('quotes')->name('quotes.')->group(function () {
        Route::name('index')->get('/', [QuoteController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [QuoteController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [QuoteController::class, 'bulkDelete']);
        Route::name('bulk.status')->post('bulk/status', [QuoteController::class, 'bulkStatus']);
        Route::name('pdf')->get('{id}/pdf', [QuoteController::class, 'pdf']);

        Route::name('edit')->get('{id}/edit', [QuoteEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [QuoteEditController::class, 'update']);
        Route::name('quoteEdit.refreshEdit')->get('{id}/edit/refresh', [QuoteEditController::class, 'refreshEdit']);
        Route::name('quoteEdit.refreshTotals')->post('edit/refresh_totals', [QuoteEditController::class, 'refreshTotals']);
        Route::name('quoteEdit.refreshTo')->post('edit/refresh_to', [QuoteEditController::class, 'refreshTo']);
        Route::name('quoteEdit.refreshFrom')->post('edit/refresh_from', [QuoteEditController::class, 'refreshFrom']);
        //Route::name('quoteEdit.updateClient')->post('edit/update_client', [QuoteEditController::class, 'updateClient']);
        Route::name('quoteEdit.updateCompanyProfile')->post('edit/update_company_profile', [QuoteEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [QuoteRecalculateController::class, 'recalculate']);
    });

    Route::prefix('quote_to_invoice')->name('quoteToInvoice.')->group(function () {
        Route::name('create')->post('create', [QuoteToInvoiceController::class, 'create']);
        Route::name('store')->post('store', [QuoteToInvoiceController::class, 'store']);
    });

    Route::prefix('quote_to_workorder')->name('quoteToWorkorder.')->group(function () {
        Route::name('create')->post('create', [QuoteToWorkorderController::class, 'create']);
        Route::name('store')->post('store', [QuoteToWorkorderController::class, 'store']);
    });

    Route::prefix('quote_mail')->name('quoteMail.')->group(function () {
        Route::name('create')->post('create', [QuoteMailController::class, 'create']);
        Route::name('store')->post('store', [QuoteMailController::class, 'store']);
    });

    Route::prefix('quote_item')->name('quoteItem.')->group(function () {
        Route::name('delete')->post('delete', [QuoteItemController::class, 'delete']);
    });
});
