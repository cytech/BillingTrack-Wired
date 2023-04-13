<?php

namespace BT\Modules\Merchant\Support;

use BT\Modules\Documents\Models\Invoice;

abstract class MerchantDriverPayable extends MerchantDriver
{
    abstract public function pay(Invoice $invoice);
}
