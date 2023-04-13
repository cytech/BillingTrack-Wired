<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Document;
use Illuminate\Queue\SerializesModels;

class DocumentViewed extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
}
