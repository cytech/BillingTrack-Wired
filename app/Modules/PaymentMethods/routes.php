<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\PaymentMethods\Controllers\PaymentMethodController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('payment_methods')->name('paymentMethods.')->group(function () {
        Route::name('index')->get('/', [PaymentMethodController::class, 'index']);
        Route::name('create')->get('create', [PaymentMethodController::class, 'create']);
        Route::name('store')->post('payment_methods', [PaymentMethodController::class, 'store']);
        Route::name('edit')->get('{paymentMethod}/edit', [PaymentMethodController::class, 'edit']);
        Route::name('update')->post('{paymentMethod}', [PaymentMethodController::class, 'update']);
        Route::name('delete')->get('{paymentMethod}/delete', [PaymentMethodController::class, 'delete']);
    });
