<?php

namespace BT\Observers;

use BT\Events\DocumentModified;
use BT\Modules\Documents\Models\DocumentItem;

class DocumentItemObserver
{
    /**
     * Handle the document item "saving" event.
     */
    public function saving(DocumentItem $documentItem): void
    {
        $item = $documentItem;

        if (! $item->display_order) {
            $displayOrder = DocumentItem::where('document_id', $item->document_id)->max('display_order');

            $displayOrder++;

            $item->display_order = $displayOrder;
        }

        if (! $item->resource_id) {
            $item->resource_id = 0;
        }
    }

    /**
     * Handle the document item "created" event.
     *
     * @param  \BT\Modules\Documents\Models\DocumentItem  $invoiceItem
     */
    public function created(DocumentItem $invoiceItem): void
    {
        // only applies to invoice items
        if ($invoiceItem->document->moduleType == 'Invoice') {
            // product numstock update
            // if inv tracking is on and invoice is sent and inventorytype is tracked , decrement onhand
            if (config('bt.updateInvProductsDefault') && $invoiceItem->invoice->status_text == 'sent') {
                if ($invoiceItem->resource_id && $invoiceItem->resource_table == 'products'
                    && $invoiceItem->product()->tracked()->get()->isNotEmpty()) {
                    $invoiceItem->product->decrement('numstock', $invoiceItem->quantity);
                    $invoiceItem->update(['is_tracked' => 1]);
                }
            }
        }
    }

    /**
     * Handle the document item "deleted" event.
     */
    public function deleted(DocumentItem $documentItem): void
    {
        event(new DocumentModified($documentItem->document));

    }
}
