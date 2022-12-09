<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\ClientCenter\Controllers\ClientCenterDashboardController;
use BT\Modules\ClientCenter\Controllers\ClientCenterInvoiceController;
use BT\Modules\ClientCenter\Controllers\ClientCenterPaymentController;
use BT\Modules\ClientCenter\Controllers\ClientCenterPublicInvoiceController;
use BT\Modules\ClientCenter\Controllers\ClientCenterPublicQuoteController;
use BT\Modules\ClientCenter\Controllers\ClientCenterPublicWorkorderController;
use BT\Modules\ClientCenter\Controllers\ClientCenterQuoteController;
use BT\Modules\ClientCenter\Controllers\ClientCenterWorkorderController;

Route::middleware('web')
    ->prefix('client_center')->name('clientCenter.')->group(function () {
        Route::get('/', [ClientCenterDashboardController::class, 'redirectToLogin']);
        Route::name('public.invoice.show')->get('invoice/{invoiceKey}', [ClientCenterPublicInvoiceController::class, 'show']);
        Route::name('public.invoice.pdf')->get('invoice/{invoiceKey}/pdf', [ClientCenterPublicInvoiceController::class, 'pdf']);
        Route::name('public.invoice.html')->get('invoice/{invoiceKey}/html', [ClientCenterPublicInvoiceController::class, 'html']);
        Route::name('public.quote.show')->get('quote/{quoteKey}', [ClientCenterPublicQuoteController::class, 'show']);
        Route::name('public.quote.pdf')->get('quote/{quoteKey}/pdf', [ClientCenterPublicQuoteController::class, 'pdf']);
        Route::name('public.quote.html')->get('quote/{quoteKey}/html', [ClientCenterPublicQuoteController::class, 'html']);
        Route::name('public.quote.approve')->get('quote/{quoteKey}/approve', [ClientCenterPublicQuoteController::class, 'approve']);
        Route::name('public.quote.reject')->get('quote/{quoteKey}/reject', [ClientCenterPublicQuoteController::class, 'reject']);
        Route::name('public.workorder.show')->get('workorder/{workorderKey}', [ClientCenterPublicWorkorderController::class, 'show']);
        Route::name('public.workorder.pdf')->get('workorder/{workorderKey}/pdf', [ClientCenterPublicWorkorderController::class, 'pdf']);
        Route::name('public.workorder.html')->get('workorder/{workorderKey}/html', [ClientCenterPublicWorkorderController::class, 'html']);
        Route::name('public.workorder.approve')->get('workorder/{workorderKey}/approve', [ClientCenterPublicWorkorderController::class, 'approve']);
        Route::name('public.workorder.reject')->get('workorder/{workorderKey}/reject', [ClientCenterPublicWorkorderController::class, 'reject']);
        Route::middleware('auth.clientCenter')->group(function () {
            Route::name('dashboard')->get('dashboard', [ClientCenterDashboardController::class, 'index']);
            Route::name('invoices')->get('invoices', [ClientCenterInvoiceController::class, 'index']);
            Route::name('quotes')->get('quotes', [ClientCenterQuoteController::class, 'index']);
            Route::name('workorders')->get('workorders', [ClientCenterWorkorderController::class, 'index']);
            Route::name('payments')->get('payments', [ClientCenterPaymentController::class, 'index']);
        });
    });
