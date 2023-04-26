<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Exports\Support\Results;

use BT\Modules\Documents\Models\DocumentItem;

class InvoiceItems implements SourceInterface
{
    public function getResults($params = [])
    {
        $invoiceItem = DocumentItem::whereHas('invoice')->select('documents.number', 'document_items.created_at', 'document_items.name',
            'document_items.description', 'document_items.quantity', 'document_items.price', 'tax_rate_1.name AS tax_rate_1_name',
            'tax_rate_1.percent AS tax_rate_1_percent', 'tax_rate_1.is_compound AS tax_rate_1_is_compound',
            'document_item_amounts.tax_1 AS tax_rate_1_amount', 'tax_rate_2.name AS tax_rate_2_name',
            'tax_rate_2.percent AS tax_rate_2_percent', 'tax_rate_2.is_compound AS tax_rate_2_is_compound',
            'document_item_amounts.tax_2 AS tax_rate_2_amount', 'document_item_amounts.subtotal', 'document_item_amounts.tax',
            'document_item_amounts.total')
            ->join('documents', 'documents.id', '=', 'document_items.document_id')
            ->join('document_item_amounts', 'document_item_amounts.item_id', '=', 'document_items.id')
            ->leftJoin('tax_rates AS tax_rate_1', 'tax_rate_1.id', '=', 'document_items.tax_rate_id')
            ->leftJoin('tax_rates AS tax_rate_2', 'tax_rate_2.id', '=', 'document_items.tax_rate_2_id')
            ->orderBy('documents.number');

        return $invoiceItem->get()->toArray();
    }
}
