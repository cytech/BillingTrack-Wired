<?php

use BT\Modules\Attachments\Controllers\AttachmentController;

Route::middleware('web')->prefix('attachments')
    ->name('attachments.')->group(function () {
        Route::name('download')->get('{urlKey}/download', [AttachmentController::class, 'download']);

        Route::middleware('auth.admin')->group(function () {
            Route::name('ajax.list')->post('ajax/list', [AttachmentController::class, 'ajaxList']);
            Route::name('ajax.delete')->post('ajax/delete', [AttachmentController::class, 'ajaxDelete']);
            Route::name('ajax.modal')->post('ajax/modal', [AttachmentController::class, 'ajaxModal']);
            Route::name('ajax.upload')->post('ajax/upload', [AttachmentController::class, 'ajaxUpload']);
            Route::name('ajax.access.update')->post('ajax/access/update', [AttachmentController::class, 'ajaxAccessUpdate']);
        });
    });
