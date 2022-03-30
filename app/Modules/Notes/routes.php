<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Notes\Controllers\NoteController;

Route::middleware(['web', 'auth'])
    ->prefix('notes')->name('notes.')->group(function () {
        Route::name('create')->post('create', [NoteController::class, 'create']);
        Route::name('delete')->post('delete', [NoteController::class, 'delete']);
    });
