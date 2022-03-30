<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Users\Controllers\PermissionsController;
use BT\Modules\Users\Controllers\RolesController;
use BT\Modules\Users\Controllers\UserController;
use BT\Modules\Users\Controllers\UserPasswordController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('users')->name('users.')->group(function () {
        Route::name('index')->get('/', [UserController::class, 'index']);
        Route::name('create')->get('create/{userType}', [UserController::class, 'create']);
        Route::name('store')->post('create/{userType}', [UserController::class, 'store']);
        Route::name('edit')->get('{id}/edit/{userType}', [UserController::class, 'edit']);
        Route::name('update')->post('{id}/edit/{userType}', [UserController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [UserController::class, 'delete']);
        Route::name('client.lookup')->get('client/lookup', [UserController::class, 'clientLookup']);

        Route::name('password.edit')->get('{id}/password/edit', [UserPasswordController::class, 'edit']);
        Route::name('password.update')->post('{id}/password/edit', [UserPasswordController::class, 'update']);

        //manage acl
        Route::middleware(['web', 'role:superadmin'])->group(function () {
            Route::name('manage_acl')->get('manage_acl', [RolesController::class, 'index']);
            Route::resource('roles', RolesController::class)->except(['index']);
            Route::resource('permissions', PermissionsController::class);
        });
    });
