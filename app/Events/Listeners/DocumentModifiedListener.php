<?php

namespace BT\Events\Listeners;

use AllowDynamicProperties;
use BT\Events\DocumentModified;
use BT\Modules\Documents\Support\DocumentCalculate;

#[AllowDynamicProperties]
class DocumentModifiedListener
{
    public function __construct(DocumentCalculate $documentCalculate)
    {
        $this->documentCalculate = $documentCalculate;
    }

    public function handle(DocumentModified $event)
    {
        // Calculate the document and item amounts
        $this->documentCalculate->calculate($event->document);
    }
}
