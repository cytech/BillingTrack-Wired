<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\TaxRates\Controllers\TaxRateController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('tax_rates')->name('taxRates.')->group(function () {
        Route::name('index')->get('/', [TaxRateController::class, 'index']);
        Route::name('create')->get('create', [TaxRateController::class, 'create']);
        Route::name('store')->post('tax_rates', [TaxRateController::class, 'store']);
        Route::name('edit')->get('{taxRate}/edit', [TaxRateController::class, 'edit']);
        Route::name('update')->post('{taxRate}', [TaxRateController::class, 'update']);
        Route::name('delete')->get('{taxRate}/delete', [TaxRateController::class, 'delete']);
    });
