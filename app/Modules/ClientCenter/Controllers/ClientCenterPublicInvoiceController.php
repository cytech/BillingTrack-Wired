<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\ClientCenter\Controllers;

use BT\Events\DocumentViewed;
use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Merchant\Support\MerchantFactory;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\DocumentStatuses;

class ClientCenterPublicInvoiceController extends Controller
{
    public function show($urlKey)
    {
        $invoice = Invoice::where('url_key', $urlKey)->first();

        app()->setLocale($invoice->client->language);

        if (! $invoice->viewed) {
            event(new DocumentViewed($invoice));
        }

        return view('client_center.invoices.public')
            ->with('invoice', $invoice)
            ->with('statuses', DocumentStatuses::statuses())
            ->with('urlKey', $urlKey)
            ->with('merchantDrivers', MerchantFactory::getDrivers(true))
            ->with('attachments', $invoice->clientAttachments);
    }

    public function pdf($urlKey)
    {
        $invoice = Invoice::with('items.taxRate', 'items.taxRate2', 'items.amount.item.invoice', 'items.invoice')
            ->where('url_key', $urlKey)->first();

        if (! $invoice->viewed) {
            event(new DocumentViewed($invoice));
        }

        $pdf = PDFFactory::create();

        $pdf->download($invoice->html, FileNames::document($invoice));
    }

    public function html($urlKey)
    {
        $invoice = Invoice::with('items.taxRate', 'items.taxRate2', 'items.amount.item.invoice', 'items.invoice')
            ->where('url_key', $urlKey)->first();

        return $invoice->html;
    }
}
