<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Currencies\Controllers\CurrencyController;

Route::name('currencies.getExchangeRate')->post('currencies/get-exchange-rate', [CurrencyController::class, 'getExchangeRate']);
Route::middleware(['web', 'auth.admin'])
    ->prefix('currencies')->name('currencies.')->group(function () {
        Route::name('index')->get('/', [CurrencyController::class, 'index']);
        Route::name('create')->get('create', [CurrencyController::class, 'create']);
        Route::name('store')->post('currencies', [CurrencyController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [CurrencyController::class, 'edit']);
        Route::name('update')->post('{id}', [CurrencyController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [CurrencyController::class, 'delete']);
//        Route::name('getExchangeRate')->post('get-exchange-rate', [CurrencyController::class, 'getExchangeRate']);
    });
