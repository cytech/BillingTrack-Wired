<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Invoices\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Invoices\Models\Invoice;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\InvoiceStatuses;
use BT\Traits\ReturnUrl;

class InvoiceController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();
        $status = request('status') === 'all_statuses' ? '' : request('status');

        return view('invoices.index')->with('status', $status);
    }

    public function delete($id)
    {
        Invoice::destroy($id);

        return redirect()->route('invoices.index')
            ->with('alert', trans('bt.record_successfully_trashed'));
    }

    public function bulkDelete()
    {
        Invoice::destroy(request('ids'));
        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);
    }

    public function bulkStatus()
    {
        Invoice::whereIn('id', request('ids'))
            ->where('invoice_status_id', '<>', InvoiceStatuses::getStatusId('paid'))
            ->update(['invoice_status_id' => request('status')]);
        return response()->json(['success' => trans('bt.status_successfully_updated')], 200);
    }

    public function pdf($id)
    {
        $invoice = Invoice::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($invoice->html, FileNames::invoice($invoice));
    }
}
