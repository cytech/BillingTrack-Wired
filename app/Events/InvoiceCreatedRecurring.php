<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Recurringinvoice;
use Illuminate\Queue\SerializesModels;

class InvoiceCreatedRecurring extends Event
{
    use SerializesModels;

    public function __construct(Invoice $invoice, Recurringinvoice $recurringInvoice)
    {
        $this->invoice          = $invoice;
        $this->recurringInvoice = $recurringInvoice;
    }
}
