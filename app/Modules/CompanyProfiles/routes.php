<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use BT\Modules\CompanyProfiles\Controllers\CompanyProfileController;

Route::middleware(['web', 'auth.admin'])
    ->prefix('company_profiles')->name('companyProfiles.')->group(function () {
        Route::name('index')->get('/', [CompanyProfileController::class, 'index']);
        Route::name('create')->get('create', [CompanyProfileController::class, 'create']);
        Route::name('store')->post('/', [CompanyProfileController::class, 'store']);
        Route::name('edit')->get('{id}/edit', [CompanyProfileController::class, 'edit']);
        Route::name('update')->post('{id}', [CompanyProfileController::class, 'update']);
        Route::name('delete')->get('{id}/delete', [CompanyProfileController::class, 'delete']);
        Route::name('ajax.modalLookup')->post('ajax/modal_lookup', [CompanyProfileController::class, 'ajaxModalLookup']);
        Route::name('deleteLogo')->post('{id}/delete_logo', [CompanyProfileController::class, 'deleteLogo']);
    });

Route::name('logo')->get('{id}/logo', [CompanyProfileController::class, 'logo']);
