<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Payments\Controllers\PaymentController;
use BT\Modules\Payments\Controllers\PaymentMailController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('payments')->name('payments.')->group(function () {
        Route::name('index')->get('/', [PaymentController::class, 'index']);
        Route::name('edit')->get('{payment}', [PaymentController::class, 'edit']);
        Route::name('update')->post('{payment}', [PaymentController::class, 'update']);
        Route::name('delete')->get('{payment}/delete', [PaymentController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [PaymentController::class, 'bulkDelete']);

        Route::prefix('payment_mail')->group(function () {
            Route::name('paymentMail.create')->post('create', [PaymentMailController::class, 'create']);
            Route::name('paymentMail.store')->post('store', [PaymentMailController::class, 'store']);
        });
    });
