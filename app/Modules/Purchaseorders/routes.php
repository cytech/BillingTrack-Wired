<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Purchaseorders\Controllers\PurchaseorderController;
use BT\Modules\Purchaseorders\Controllers\PurchaseorderEditController;
use BT\Modules\Purchaseorders\Controllers\PurchaseorderItemController;
use BT\Modules\Purchaseorders\Controllers\PurchaseorderMailController;
use BT\Modules\Purchaseorders\Controllers\PurchaseorderRecalculateController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('purchaseorders')->name('purchaseorders.')->group(function () {
        Route::name('index')->get('/', [PurchaseorderController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [PurchaseorderController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [PurchaseorderController::class, 'bulkDelete']);
        Route::name('bulk.status')->post('bulk/status', [PurchaseorderController::class, 'bulkStatus']);
        Route::name('pdf')->get('{id}/pdf', [PurchaseorderController::class, 'pdf']);
        Route::name('receive')->post('receive', [PurchaseorderController::class, 'receive']);
        Route::name('receive_items')->post('receive_items', [PurchaseorderController::class, 'receiveItems']);

        Route::name('edit')->get('{id}/edit', [PurchaseorderEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [PurchaseorderEditController::class, 'update']);
        Route::name('purchaseorderEdit.refreshEdit')->get('{id}/edit/refresh', [PurchaseorderEditController::class, 'refreshEdit']);
        Route::name('purchaseorderEdit.refreshTotals')->post('edit/refresh_totals', [PurchaseorderEditController::class, 'refreshTotals']);
        Route::name('purchaseorderEdit.refreshTo')->post('edit/refresh_to', [PurchaseorderEditController::class, 'refreshTo']);
        Route::name('purchaseorderEdit.refreshFrom')->post('edit/refresh_from', [PurchaseorderEditController::class, 'refreshFrom']);
        //Route::name('purchaseorderEdit.updateVendor')->post('edit/update_vendor', [PurchaseorderEditController::class, 'updateVendor']);
        Route::name('purchaseorderEdit.updateCompanyProfile')->post('edit/update_company_profile', [PurchaseorderEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [PurchaseorderRecalculateController::class, 'recalculate']);
    });

    Route::prefix('purchaseorder_mail')->name('purchaseorderMail.')->group(function () {
        Route::name('create')->post('create', [PurchaseorderMailController::class, 'create']);
        Route::name('store')->post('store', [PurchaseorderMailController::class, 'store']);
    });

    Route::prefix('purchaseorder_item')->name('purchaseorderItem.')->group(function () {
        Route::name('delete')->post('delete', [PurchaseorderItemController::class, 'delete']);
    });
});
