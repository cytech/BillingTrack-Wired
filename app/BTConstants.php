<?php

if (!defined('APP_NAME')) {
    define('APP_NAME', env('APP_NAME', 'BillingTrack'));

    // constant [number,fullnamespace,module_type]
    define('DOCUMENT_TYPE_QUOTE', ['document_type'  => 1,
                                   'modulefullname' => addslashes(\BT\Modules\Documents\Models\Document::class),
                                   'module_type'    => 'Quote']);
    define('DOCUMENT_TYPE_WORKORDER', ['document_type'  => 2,
                                       'modulefullname' => addslashes(\BT\Modules\Documents\Models\Document::class),
                                       'module_type'    => 'Workorder']);
    define('DOCUMENT_TYPE_INVOICE', ['document_type'  => 3,
                                     'modulefullname' => addslashes(\BT\Modules\Documents\Models\Document::class),
                                     'module_type'    => 'Invoice']);
    define('DOCUMENT_TYPE_RECURRINGINVOICE', ['document_type'  => 4,
                                      'modulefullname' => addslashes(\BT\Modules\RecurringInvoices\Models\RecurringInvoice::class),
                                      'module_type'    => 'RecurringInvoice']);
    define('DOCUMENT_TYPE_PURCHASEORDER', ['document_type'  => 5,
                                           'modulefullname' => addslashes(\BT\Modules\Documents\Models\Document::class),
                                           'module_type'    => 'Purchaseorder']);
}
