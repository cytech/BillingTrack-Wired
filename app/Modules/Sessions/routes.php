<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\Sessions\Controllers\SessionController;

Route::middleware('web')->group(function () {
    Route::name('session.login')->get('login', [SessionController::class, 'login']);
    Route::name('session.attempt')->post('login', [SessionController::class, 'attempt']);
    Route::name('session.logout')->get('logout', [SessionController::class, 'logout']);
});
