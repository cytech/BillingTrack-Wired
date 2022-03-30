<?php

use BT\Modules\Merchant\Controllers\MerchantController;

Route::middleware('web')
    ->prefix('merchant')->name('merchant.')->group(function () {
        Route::name('pay')->post('pay', [MerchantController::class, 'pay']);
        Route::name('cancelUrl')->get('{driver}/{urlKey}/cancel', [MerchantController::class, 'cancelUrl']);
        Route::name('returnUrl')->get('{driver}/{urlKey}/return', [MerchantController::class, 'returnUrl']);
        Route::name('webhookUrl')->post('{driver}/{urlKey}/webhook', [MerchantController::class, 'webhookUrl']);
    });
