<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Import\Controllers\ImportController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('import')->name('import.')->group(function () {
        Route::name('index')->get('/', [ImportController::class, 'index']);
        Route::name('map')->get('map/{import_type}', [ImportController::class, 'mapImport']);
        Route::name('upload')->post('upload', [ImportController::class, 'upload']);
        Route::name('map.submit')->post('map/{import_type}', [ImportController::class, 'mapImportSubmit']);
    });
