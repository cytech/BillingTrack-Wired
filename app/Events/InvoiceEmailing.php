<?php

namespace BT\Events;

use BT\Modules\Invoices\Models\Invoice;
use Illuminate\Queue\SerializesModels;

class InvoiceEmailing extends Event
{
    use SerializesModels;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
