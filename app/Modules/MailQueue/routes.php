<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\MailQueue\Controllers\MailLogController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('mail_log')->name('mailLog.')->group(function () {
        Route::name('index')->get('/', [MailLogController::class, 'index']);
        Route::name('content')->post('content', [MailLogController::class, 'content']);
        Route::name('delete')->get('{id}/delete', [MailLogController::class, 'delete']);
    });
