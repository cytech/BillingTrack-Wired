<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Purchaseorder;
use Illuminate\Queue\SerializesModels;

class PurchaseorderEmailing extends Event
{
    use SerializesModels;

    public function __construct(Purchaseorder $purchaseorder)
    {
        $this->purchaseorder = $purchaseorder;
    }
}
