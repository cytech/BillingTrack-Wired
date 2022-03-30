<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Products\Controllers\ProductController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('products')->name('products.')->group(function () {
        Route::name('index')->get('/', [ProductController::class, 'index']);
        Route::name('create')->get('create', [ProductController::class, 'create']);
        Route::name('store')->post('create', [ProductController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [ProductController::class, 'edit']);
        Route::name('update')->put('{id}/edit', [ProductController::class, 'update']);
    });
