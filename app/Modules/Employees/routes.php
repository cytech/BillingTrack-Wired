<?php

use BT\Modules\Employees\Controllers\EmployeeController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('employees')->name('employees.')->group(function () {
        Route::name('index')->get('/', [EmployeeController::class, 'index']);
        Route::name('create')->get('create', [EmployeeController::class, 'create']);
        Route::name('store')->post('create', [EmployeeController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [EmployeeController::class, 'edit']);
        Route::name('update')->put('{id}/edit', [EmployeeController::class, 'update']);
    });
