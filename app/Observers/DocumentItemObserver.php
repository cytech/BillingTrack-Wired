<?php

namespace BT\Observers;

use BT\Events\DocumentModified;
use BT\Modules\Documents\Models\DocumentItem;

class DocumentItemObserver
{
    /**
     * Handle the document item "saving" event.
     *
     * @param  \BT\Modules\Documents\Models\DocumentItem  $documentItem
     * @return void
     */
    public function saving(DocumentItem $documentItem): void
    {
        $item = $documentItem;

        if (!$item->display_order)
        {
            $displayOrder = DocumentItem::where('document_id', $item->document_id)->max('display_order');

            $displayOrder++;

            $item->display_order = $displayOrder;
        }

        if (!$item->resource_id){
            $item->resource_id = 0;
        }
    }

    /**
     * Handle the document item "deleted" event.
     *
     * @param  \BT\Modules\Documents\Models\DocumentItem  $documentItem
     * @return void
     */
    public function deleted(DocumentItem $documentItem): void
    {
        event(new DocumentModified($documentItem->document));

    }

}
