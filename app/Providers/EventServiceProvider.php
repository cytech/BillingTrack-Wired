<?php

namespace BT\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'BT\Events\DocumentModified' => [
            'BT\Events\Listeners\DocumentModifiedListener',
        ],

        'BT\Events\DocumentEmailing' => [
            'BT\Events\Listeners\DocumentEmailingListener',
        ],

        'BT\Events\DocumentEmailed' => [
            'BT\Events\Listeners\DocumentEmailedListener',
        ],

        'BT\Events\DocumentApproved' => [
            'BT\Events\Listeners\DocumentApprovedListener',
        ],

        'BT\Events\DocumentRejected' => [
            'BT\Events\Listeners\DocumentRejectedListener',
        ],

        'BT\Events\DocumentViewed' => [
            'BT\Events\Listeners\DocumentViewedListener',
        ],

        'BT\Events\CheckAttachment' => [
            'BT\Events\Listeners\CheckAttachmentListener',
        ],

        'BT\Events\InvoiceCreatedRecurring' => [
            'BT\Events\Listeners\InvoiceCreatedRecurringListener',
        ],

        'BT\Events\RecurringInvoiceModified' => [
            'BT\Events\Listeners\RecurringInvoiceModifiedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
