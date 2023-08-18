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

use BT\Events\DocumentModified;
use BT\Http\Controllers\Controller;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Documents\Requests\DocumentUpdateRequest;
use BT\Modules\Documents\Support\DocumentTemplates;
use BT\Modules\Groups\Models\Group;
use BT\Modules\ItemLookups\Models\ItemLookup;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Support\Frequency;
use BT\Support\Statuses\DocumentStatuses;
use BT\Traits\ReturnUrl;

class DocumentEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $this->setPreviousUrl();

        $document = Document::with(['items.amount.item.document.currency'])->find($id);

        return view('documents.edit')
            ->with('document', $document)
            ->with('statuses', DocumentStatuses::listsType($document->module_type))
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable($document->view_directory_name)->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', DocumentTemplates::lists($document->module_type))
            ->with('frequencies', Frequency::lists())
            ->with('groups', Group::getList())
            ->with('itemCount', count($document->documentItems));
    }

    public function update(DocumentUpdateRequest $request, $id)
    {
        $input = $request->except(['items', 'custom', 'apply_exchange_rate']);

        // Save the document.
        $document = Document::find($id);
        $oldstatus = $document->document_status_id;
        $newstatus = $document['document_status_id'];
        $document->fill($input);
        $document->save();

        // Save the custom fields.
        $document->custom->update($request->input('custom', []));
        // Save the items.
        foreach ($request->input('items') as $item) {

            if ($request->input('apply_exchange_rate')) {
                $item['price'] = $item['price'] * $document->exchange_rate;
            }

            if (! isset($item['id']) or (! $item['id'])) {
                //if item_lookup and item_lookup has resource, remap item to resource
                if ($item['resource_table'] == 'item_lookups') {
                    $il = ItemLookup::find($item['resource_id']);
                    if ($il->resource_table) {
                        $item['resource_table'] = $il->resource_table;
                        $item['resource_id'] = $il->resource_id;
                    }
                }
                DocumentItem::create($item);
                // product numstock update - moved to DocumentItemObserver:created()
            } else {
                $documentItem = DocumentItem::find($item['id']);
                // product numstock updating on invoices only
                if ($documentItem->document->moduleType == 'Invoice' && config('bt.updateInvProductsDefault')) {
                    $qtydiff = abs($documentItem->quantity - $item['quantity']);
                    // if quantity changed
                    if ($qtydiff && $documentItem->is_tracked) {
                        switch ($documentItem->quantity <=> $item['quantity']) {
                            case 0:
                                break;
                            case -1:
                                $documentItem->product->decrement('numstock', $qtydiff);
                                break;
                            case 1:
                                $documentItem->product->increment('numstock', $qtydiff);
                                break;
                        }
                    }

                    // if status changed to sent from draft or canceled
                    if (($oldstatus == 1 || $oldstatus == 5) && $newstatus == 2) {
                        // if item has NOT already been tracked and item is tracked inventory, decrement onhand
                        if ($documentItem->resource_id && ! $documentItem->is_tracked && $documentItem->resource_table == 'products'
                            && $documentItem->product()->tracked()->get()->isNotEmpty()) {
                            $documentItem->product->decrement('numstock', $item['quantity']);
                            $documentItem->is_tracked = 1;
                        }
                    }
                    //if status changed from sent to draft or canceled
                    if ($oldstatus == 2 && ($newstatus == 1 || $newstatus == 5)) {
                        // if item has already been tracked and item is tracked inventory, increment onhand
                        if ($documentItem->resource_id && $documentItem->is_tracked && $documentItem->resource_table == 'products'
                            && $documentItem->product()->tracked()->get()->isNotEmpty()) {
                            $documentItem->product->increment('numstock', $item['quantity']);
                            $documentItem->is_tracked = 0;
                        }
                    }
                }
                $documentItem->fill($item);
                $documentItem->save();
            }
        }

        event(new DocumentModified($document));

        return response()->json(['success' => true], 200);
    }

    public function refreshEdit($id)
    {
        $document = Document::with(['items.amount.item.document.currency'])->find($id);

        return view('documents._edit')
            ->with('document', $document)
            ->with('statuses', DocumentStatuses::listsType($document->module_type))
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable($document->view_directory_name)->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', DocumentTemplates::lists($document->module_type))
            ->with('frequencies', Frequency::lists())
            ->with('groups', Group::getList())
            ->with('itemCount', count($document->documentItems));
    }

    public function refreshTotals()
    {
        return view('documents._edit_totals')
            ->with('document', Document::with(['items.amount.item.document.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('documents._edit_to')
            ->with('document', Document::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('documents._edit_from')
            ->with('document', Document::find(request('id')));
    }

    public function updateCompanyProfile()
    {
        Document::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}
