<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Dashboard\Controllers\DashboardController;

Route::middleware(['web', 'auth.admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::name('dashboard.index')->get('dashboard', [DashboardController::class, 'index']);
    //clear views from storage
    Route::get('/viewclear', [function () {
        Artisan::call('view:clear');
        return redirect()->route('dashboard.index');
    }]);
});
