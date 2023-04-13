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
use BT\Modules\Documents\Models\Workorder;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\DocumentStatuses;

class ClientCenterPublicWorkorderController extends Controller
{
    public function show($urlKey)
    {
        $workorder = Workorder::where('url_key', $urlKey)->first();

        app()->setLocale($workorder->client->language);

        if (!$workorder->viewed) {
            event(new DocumentViewed($workorder));
        }

        return view('client_center.workorders.public')
            ->with('workorder', $workorder)
            ->with('statuses', DocumentStatuses::statuses())
            ->with('urlKey', $urlKey)
            ->with('attachments', $workorder->clientAttachments);
    }

    public function pdf($urlKey)
    {
        $workorder = Workorder::with('items.taxRate', 'items.taxRate2', 'items.amount.item.workorder', 'items.workorder')
            ->where('url_key', $urlKey)->first();

        if (!$workorder->viewed) {
            event(new DocumentViewed($workorder));
        }

        $pdf = PDFFactory::create();

        $pdf->download($workorder->html, FileNames::workorder($workorder));
    }

    public function html($urlKey)
    {
        $workorder = Workorder::with('items.taxRate', 'items.taxRate2', 'items.amount.item.workorder', 'items.workorder')
            ->where('url_key', $urlKey)->first();

        return $workorder->html;
    }

    public function approve($urlKey)
    {
        $workorder = Workorder::where('url_key', $urlKey)->first();

        $workorder->document_status_id = DocumentStatuses::getStatusId('approved');

        $workorder->save();

        event(new DocumentApproved($workorder));

        return redirect()->route('clientCenter.public.workorder.show', [$urlKey]);
    }

    public function reject($urlKey)
    {
        $workorder = Workorder::where('url_key', $urlKey)->first();

        $workorder->document_status_id = DocumentStatuses::getStatusId('rejected');

        $workorder->save();

        event(new DocumentRejected($workorder));

        return redirect()->route('clientCenter.public.workorder.show', [$urlKey]);
    }
}
