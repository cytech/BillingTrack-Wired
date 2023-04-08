<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Invoice;
use Illuminate\Queue\SerializesModels;

class InvoiceViewed extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
