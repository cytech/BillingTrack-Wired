<?php

namespace BT\Events\Listeners;

use BT\Events\DocumentEmailed;
use BT\Support\Statuses\DocumentStatuses;

class DocumentEmailedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DocumentEmailed $event
     * @return void
     */
    public function handle(DocumentEmailed $event)
    {
        // Change the status to sent if the status is currently draft
        if ($event->document->document_status_id == DocumentStatuses::getStatusId('draft')) {
            $event->document->document_status_id = DocumentStatuses::getStatusId('sent');
            $event->document->save();
        }
    }
}
