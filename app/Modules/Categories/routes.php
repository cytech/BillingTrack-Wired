<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Categories\Controllers\CategoriesController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('categories')->name('categories.')->group(function () {
        Route::name('index')->get('/', [CategoriesController::class, 'index']);
        Route::name('edit')->get('{id}/edit', [CategoriesController::class, 'edit']);
        Route::name('update')->put('{id}/edit', [CategoriesController::class, 'update']);
        Route::name('create')->get('create', [CategoriesController::class, 'create']);
        Route::name('store')->post('create', [CategoriesController::class, 'store']);
    });
