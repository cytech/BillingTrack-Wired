<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Quotes\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Quotes\Models\Quote;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\QuoteStatuses;
use BT\Traits\ReturnUrl;

class QuoteController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();
        $status = request('status') === 'all_statuses' ? '' : request('status');

        return view('quotes.index')->with('status', $status);
    }

    public function delete($id)
    {
        Quote::destroy($id);

        return redirect()->route('quotes.index')
            ->with('alert', trans('bt.record_successfully_trashed'));
    }

    public function bulkDelete()
    {
        Quote::destroy(request('ids'));
        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);
    }

    public function bulkStatus()
    {
        Quote::whereIn('id', request('ids'))->update(['quote_status_id' => request('status')]);

        return response()->json(['success' => trans('bt.status_successfully_updated')], 200);
    }

    public function pdf($id)
    {
        $quote = Quote::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($quote->html, FileNames::quote($quote));
    }
}
