<?php

namespace BT\Events\Listeners;

use BT\Events\DocumentViewed;

class DocumentViewedListener
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
     * @param  DocumentViewed $event
     * @return void
     */
    public function handle(DocumentViewed $event)
    {
        if (request('disableFlag') != 1)
        {
            if (auth()->guest() or auth()->user()->user_type == 'client')
            {
                $event->document->activities()->create(['activity' => 'public.viewed']);
                $event->document->viewed = 1;
                $event->document->save();
            }
        }
    }
}
