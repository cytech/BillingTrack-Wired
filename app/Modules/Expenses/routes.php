<?php

use BT\Modules\Expenses\Controllers\ExpenseBillController;
use BT\Modules\Expenses\Controllers\ExpenseController;
use BT\Modules\Expenses\Controllers\ExpenseLookupController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('expenses')->name('expenses.')->group(function () {
        Route::name('index')->get('/', [ExpenseController::class, 'index']);
        Route::name('create')->get('create', [ExpenseController::class, 'create']);
        Route::name('store')->post('create', [ExpenseController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [ExpenseController::class, 'edit']);
        Route::name('update')->post('{id}/edit', [ExpenseController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [ExpenseController::class, 'delete']);
        Route::name('bulk.delete')->post('bulk/delete', [ExpenseController::class, 'bulkDelete']);

        Route::prefix('bill')->name('expenseBill.')->group(function () {
            Route::name('create')->post('create', [ExpenseBillController::class, 'create']);
            Route::name('store')->post('store', [ExpenseBillController::class, 'store']);
        });

        Route::name('lookupCategory')->get('lookup/category', [ExpenseLookupController::class, 'lookupCategory']);
        Route::name('lookupVendor')->get('lookup/vendor', [ExpenseLookupController::class, 'lookupVendor']);

    });
