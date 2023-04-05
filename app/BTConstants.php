<?php

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\RecurringInvoices\Models\RecurringInvoice;

if (!defined('APP_NAME')) {
    define('APP_NAME', env('APP_NAME', 'BillingTrack'));

    // constant [number,fullnamespace,module_type]
    define('DOCUMENT_TYPE_QUOTE', ['document_type'  => 1,
                                   'modulefullname' => Quote::class,
                                   'module_type'    => 'Quote']);
    define('DOCUMENT_TYPE_WORKORDER', ['document_type'  => 2,
                                       'modulefullname' => Workorder::class,
                                       'module_type'    => 'Workorder']);
    define('DOCUMENT_TYPE_INVOICE', ['document_type'  => 3,
                                     'modulefullname' => Invoice::class,
                                     'module_type'    => 'Invoice']);
    define('DOCUMENT_TYPE_RECURRINGINVOICE', ['document_type'  => 4,
                                      'modulefullname' => RecurringInvoice::class,
                                      'module_type'    => 'RecurringInvoice']);
    define('DOCUMENT_TYPE_PURCHASEORDER', ['document_type'  => 5,
                                           'modulefullname' => Purchaseorder::class,
                                           'module_type'    => 'Purchaseorder']);
}
