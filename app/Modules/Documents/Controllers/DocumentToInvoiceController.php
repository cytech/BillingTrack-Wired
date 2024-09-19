<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Requests\DocumentToInvoiceRequest;
use BT\Modules\Groups\Models\Group;
use BT\Support\ConvertToModule;
use BT\Support\DateFormatter;

class DocumentToInvoiceController extends Controller
{
    private $documentToInvoice;

    public function __construct(ConvertToModule $documentToInvoice)
    {
        $this->documentToInvoice = $documentToInvoice;
    }

    public function create()
    {
        return view('documents._modal_document_to_invoice')
            ->with('title', request('title'))
            ->with('document_id', request('document_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', Group::getList())
            ->with('user_id', auth()->user()->id)
            ->with('invoice_date', request('invoice_date'));
    }

    public function store(DocumentToInvoiceRequest $request)
    {
        $document = Document::find($request->input('document_id'));

        $invoice = $this->documentToInvoice->convert(
            $document,
            $request->input('document_date'),
            DateFormatter::incrementDateByDays($request->input('document_date'), $document->client->client_terms),
            $request->input('group_id'),
            'Invoice'
        );

        return response()->json(['redirectTo' => route('documents.edit', ['id' => $invoice->id])], 200);
    }
}
