<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Expenses\Controllers;

use BT\Events\DocumentModified;
use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Expenses\Requests\ExpenseBillRequest;

class ExpenseBillController extends Controller
{
    public function create()
    {
        $expense = Expense::defaultQuery()->find(request('id'));

        $clientInvoices = $expense->client->invoices()->orderBy('created_at', 'desc')->statusIn([
            'draft',
            'sent',
        ])->get();

        $invoices = [];

        foreach ($clientInvoices as $invoice) {
            $invoices[$invoice->id] = $invoice->formatted_created_at.' - '.$invoice->number.' '.$invoice->summary;
        }

        return view('expenses._modal_bill')
            ->with('expense', $expense)
            ->with('invoices', $invoices)
            ->with('redirectTo', request('redirectTo'));
    }

    public function store(ExpenseBillRequest $request)
    {
        $expense = Expense::find(request('id'));

        $expense->invoice_id = request('invoice_id');

        $expense->save();

        if (request('add_line_item')) {
            $item = [
                'document_id' => request('invoice_id'),
                'name' => request('item_name'),
                'description' => request('item_description'),
                'quantity' => 1,
                'price' => $expense->amount,
            ];

            DocumentItem::create($item);
            event(new DocumentModified($expense->invoice));

        }
    }
}
