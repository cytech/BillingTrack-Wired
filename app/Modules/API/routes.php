<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\API\Controllers\ApiClientController;
use BT\Modules\API\Controllers\ApiInvoiceController;
use BT\Modules\API\Controllers\ApiKeyController;
use BT\Modules\API\Controllers\ApiPaymentController;
use BT\Modules\API\Controllers\ApiQuoteController;
use BT\Modules\API\Controllers\ApiWorkorderController;

Route::middleware('web')->prefix('api')->group(function () {
    Route::middleware('auth.admin')->group(function () {
        Route::name('api.generateKeys')->post('generate_keys', [ApiKeyController::class, 'generateKeys']);
    });
    Route::middleware('auth.api')->group(function () {
        Route::post('clients/list', [ApiClientController::class, 'lists']);
        Route::post('clients/show', [ApiClientController::class, 'show']);
        Route::post('clients/store', [ApiClientController::class, 'store']);
        Route::post('clients/update', [ApiClientController::class, 'update']);
        Route::post('clients/delete', [ApiClientController::class, 'delete']);

        Route::post('quotes/list', [ApiQuoteController::class, 'lists']);
        Route::post('quotes/show', [ApiQuoteController::class, 'show']);
        Route::post('quotes/store', [ApiQuoteController::class, 'store']);
        Route::post('quotes/items/add', [ApiQuoteController::class, 'addItem']);
        Route::post('quotes/delete', [ApiQuoteController::class, 'delete']);

        Route::post('workorders/list', [ApiWorkorderController::class, 'lists']);
        Route::post('workorders/show', [ApiWorkorderController::class, 'show']);
        Route::post('workorders/store', [ApiWorkorderController::class, 'store']);
        Route::post('workorders/items/add', [ApiWorkorderController::class, 'addItem']);
        Route::post('workorders/delete', [ApiWorkorderController::class, 'delete']);

        Route::post('invoices/list', [ApiInvoiceController::class, 'lists']);
        Route::post('invoices/show', [ApiInvoiceController::class, 'show']);
        Route::post('invoices/store', [ApiInvoiceController::class, 'store']);
        Route::post('invoices/items/add', [ApiInvoiceController::class, 'addItem']);
        Route::post('invoices/delete', [ApiInvoiceController::class, 'delete']);

        Route::post('payments/list', [ApiPaymentController::class, 'lists']);
        Route::post('payments/show', [ApiPaymentController::class, 'show']);
        Route::post('payments/store', [ApiPaymentController::class, 'store']);
        Route::post('payments/delete', [ApiPaymentController::class, 'delete']);
    });
});
