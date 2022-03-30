<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Exports\Controllers\ExportController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('export')->name('export.')->group(function () {
        Route::name('index')->get('/', [ExportController::class, 'index']);
        Route::name('export')->post('{export}', [ExportController::class, 'export']);
    });
