<?php

if (! defined('APP_NAME')) {
    define('APP_NAME', env('APP_NAME', 'BillingTrack'));

    define('INVOICE_TYPE_QUOTE', 1);
    define('INVOICE_TYPE_WORKORDER', 2);
    define('INVOICE_TYPE_INVOICE', 3);
    define('INVOICE_TYPE_RECURRINGINVOICE', 4);
    define('INVOICE_TYPE_PURCHASEORDER', 5);
}
