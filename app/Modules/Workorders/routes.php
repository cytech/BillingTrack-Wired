<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Employees\Controllers\EmployeeController;
use BT\Modules\Products\Controllers\ProductController;
use BT\Modules\Workorders\Controllers\WorkorderController;
use BT\Modules\Workorders\Controllers\WorkorderEditController;
use BT\Modules\Workorders\Controllers\WorkorderItemController;
use BT\Modules\Workorders\Controllers\WorkorderMailController;
use BT\Modules\Workorders\Controllers\WorkorderRecalculateController;
use BT\Modules\Workorders\Controllers\WorkorderToInvoiceController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::prefix('workorders')->name('workorders.')->group(function () {
        Route::name('index')->get('/', [WorkorderController::class, 'index']);
        Route::name('delete')->get('{id}/delete', [WorkorderController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [WorkorderController::class, 'bulkDelete']);
        Route::name('bulk.status')->post('bulk/status', [WorkorderController::class, 'bulkStatus']);
        Route::name('pdf')->get('{id}/pdf', [WorkorderController::class, 'pdf']);

        Route::name('edit')->get('{id}/edit', [WorkorderEditController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [WorkorderEditController::class, 'update']);
        Route::name('workorderEdit.refreshEdit')->get('{id}/edit/refresh', [WorkorderEditController::class, 'refreshEdit']);
        Route::name('workorderEdit.refreshTotals')->post('edit/refresh_totals', [WorkorderEditController::class, 'refreshTotals']);
        Route::name('workorderEdit.refreshTo')->post('edit/refresh_to', [WorkorderEditController::class, 'refreshTo']);
        Route::name('workorderEdit.refreshFrom')->post('edit/refresh_from', [WorkorderEditController::class, 'refreshFrom']);
        //Route::name('workorderEdit.updateClient')->post('edit/update_client', [WorkorderEditController::class, 'updateClient']);
        Route::name('workorderEdit.updateCompanyProfile')->post('edit/update_company_profile', [WorkorderEditController::class, 'updateCompanyProfile']);

        Route::name('recalculate')->post('recalculate', [WorkorderRecalculateController::class, 'recalculate']);
    });

    Route::prefix('workorder_to_invoice')->name('workorderToInvoice.')->group(function () {
        Route::name('create')->post('create', [WorkorderToInvoiceController::class, 'create']);
        Route::name('store')->post('store', [WorkorderToInvoiceController::class, 'store']);
    });

    Route::prefix('workorder_mail')->name('workorderMail.')->group(function () {
        Route::name('create')->post('create', [WorkorderMailController::class, 'create']);
        Route::name('store')->post('store', [WorkorderMailController::class, 'store']);
    });

    Route::prefix('workorder_item')->name('workorderItem.')->group(function () {
        Route::name('delete')->post('delete', [WorkorderItemController::class, 'delete']);
    });

});
Route::middleware(['web', 'auth.admin'])->group(function () {
//resource and employee force update
    Route::get('/forceProductUpdate/{ret}', [ProductController::class, 'forceLUTupdate']);
    Route::get('/forceEmployeeUpdate/{ret}', [EmployeeController::class, 'forceLUTupdate']);
});
