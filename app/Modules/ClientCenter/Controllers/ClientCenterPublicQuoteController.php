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

use BT\Events\DocumentApproved;
use BT\Events\DocumentRejected;
use BT\Events\DocumentViewed;
use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Models\Quote;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\DocumentStatuses;

class ClientCenterPublicQuoteController extends Controller
{
    public function show($urlKey)
    {
        $quote = Quote::where('url_key', $urlKey)->first();

        app()->setLocale($quote->client->language);

        if (! $quote->viewed) {
            event(new DocumentViewed($quote));
        }

        return view('client_center.quotes.public')
            ->with('quote', $quote)
            ->with('statuses', DocumentStatuses::statuses())
            ->with('urlKey', $urlKey)
            ->with('attachments', $quote->clientAttachments);
    }

    public function pdf($urlKey)
    {
        $quote = Quote::with('items.taxRate', 'items.taxRate2', 'items.amount.item.quote', 'items.quote')
            ->where('url_key', $urlKey)->first();

        if (! $quote->viewed) {
            event(new DocumentViewed($quote));
        }

        $pdf = PDFFactory::create();

        $pdf->download($quote->html, FileNames::document($quote));
    }

    public function html($urlKey)
    {
        $quote = Quote::with('items.taxRate', 'items.taxRate2', 'items.amount.item.quote', 'items.quote')
            ->where('url_key', $urlKey)->first();

        return $quote->html;
    }

    public function approve($urlKey)
    {
        $quote = Quote::where('url_key', $urlKey)->first();

        $quote->document_status_id = DocumentStatuses::getStatusId('approved');

        $quote->save();

        event(new DocumentApproved($quote));

        return redirect()->route('clientCenter.public.quote.show', [$urlKey]);
    }

    public function reject($urlKey)
    {
        $quote = Quote::where('url_key', $urlKey)->first();

        $quote->document_status_id = DocumentStatuses::getStatusId('rejected');

        $quote->save();

        event(new DocumentRejected($quote));

        return redirect()->route('clientCenter.public.quote.show', [$urlKey]);
    }
}
