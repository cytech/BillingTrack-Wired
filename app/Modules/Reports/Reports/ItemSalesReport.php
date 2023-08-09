<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Reports\Reports;

use BT\Modules\Documents\Models\DocumentItem;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\NumberFormatter;
use BT\Support\Statuses\DocumentStatuses;

class ItemSalesReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date'   => DateFormatter::format($toDate),
            'records'   => [],
        ];

        $items = DocumentItem::byDateRange($fromDate, $toDate)
            ->select('document_items.name AS item_name', 'document_items.quantity AS item_quantity',
                'document_items.price AS item_price', 'clients.name AS client_name', 'documents.number AS invoice_number',
                'documents.document_date AS invoice_date', 'documents.exchange_rate AS invoice_exchange_rate',
                'document_item_amounts.subtotal', 'document_item_amounts.tax', 'document_item_amounts.total')
            ->join('documents', 'documents.id', '=', 'document_items.document_id')
            ->join('document_item_amounts', 'document_item_amounts.item_id', '=', 'document_items.id')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->where('documents.document_status_id', '<>', DocumentStatuses::getStatusId('canceled'))
            ->where('documents.document_status_id', '<>', DocumentStatuses::getStatusId('draft'))
            ->orderBy('document_items.name');

        if ($companyProfileId)
        {
            $items->where('invoices.company_profile_id', $companyProfileId);
        }

        $items = $items->get();

        foreach ($items as $item)
        {
            $results['records'][$item->item_name]['items'][] = [
                'client_name'    => $item->client_name,
                'invoice_number' => $item->invoice_number,
                'date'           => DateFormatter::format($item->invoice_date),
                'price'          => CurrencyFormatter::format($item->item_price / $item->invoice_exchange_rate),
                'quantity'       => NumberFormatter::format($item->item_quantity),
                'subtotal'       => CurrencyFormatter::format($item->subtotal / $item->invoice_exchange_rate),
                'tax'            => CurrencyFormatter::format($item->tax / $item->invoice_exchange_rate),
                'total'          => CurrencyFormatter::format($item->total / $item->invoice_exchange_rate),
            ];

            if (isset($results['records'][$item->item_name]['totals']))
            {
                $results['records'][$item->item_name]['totals']['quantity'] += $item->quantity;
                $results['records'][$item->item_name]['totals']['subtotal'] += round($item->subtotal / $item->invoice_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['tax'] += round($item->tax / $item->invoice_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['total'] += round($item->total / $item->invoice_exchange_rate, 2);
            }
            else
            {
                $results['records'][$item->item_name]['totals']['quantity'] = $item->quantity;
                $results['records'][$item->item_name]['totals']['subtotal'] = round($item->subtotal / $item->invoice_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['tax']      = round($item->tax / $item->invoice_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['total']    = round($item->total / $item->invoice_exchange_rate, 2);
            }
        }

        foreach ($results['records'] as $key => $result)
        {
            $results['records'][$key]['totals']['quantity'] = NumberFormatter::format($results['records'][$key]['totals']['quantity']);
            $results['records'][$key]['totals']['subtotal'] = CurrencyFormatter::format($results['records'][$key]['totals']['subtotal']);
            $results['records'][$key]['totals']['tax']      = CurrencyFormatter::format($results['records'][$key]['totals']['tax']);
            $results['records'][$key]['totals']['total']    = CurrencyFormatter::format($results['records'][$key]['totals']['total']);
        }

        return $results;
    }
}
