<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Document;
use Illuminate\Queue\SerializesModels;

class DocumentApproved extends Event
{
    use SerializesModels;

    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->module_type = $document->module_type;
    }
}
