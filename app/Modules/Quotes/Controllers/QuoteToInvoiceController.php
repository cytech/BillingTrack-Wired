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
use BT\Modules\Groups\Models\Group;
use BT\Modules\Quotes\Models\Quote;
use BT\Modules\Quotes\Requests\QuoteToInvoiceRequest;
use BT\Support\ConvertToModule;
use BT\Support\DateFormatter;

class QuoteToInvoiceController extends Controller
{
    private $quoteToInvoice;

    public function __construct(ConvertToModule $quoteToInvoice)
    {
        $this->quoteToInvoice = $quoteToInvoice;
    }

    public function create()
    {
        return view('quotes._modal_quote_to_invoice')
            ->with('quote_id', request('quote_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', Group::getList())
            ->with('user_id', auth()->user()->id)
            ->with('invoice_date', date('Y-m-d'));
    }

    public function store(QuoteToInvoiceRequest $request)
    {
        $quote = Quote::find($request->input('quote_id'));

        $invoice = $this->quoteToInvoice->convert(
            $quote,
            $request->input('invoice_date'),
            DateFormatter::incrementDateByDays($request->input('invoice_date'), $quote->client->client_terms),
            $request->input('group_id'),
            'Invoice'
        );

        return response()->json(['redirectTo' => route('invoices.edit', ['id' => $invoice->id])], 200);
    }
}
