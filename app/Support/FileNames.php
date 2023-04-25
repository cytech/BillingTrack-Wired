<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support;

class FileNames
{
    public static function document($document)
    {
        switch ($document->module_type) {
            case 'Quote':
                return trans('bt.quote') . '_' . str_replace('/', '-', $document->number) . '.pdf';
            case 'Workorder':
                return trans('bt.workorder') . '_' . str_replace('/', '-', $document->number) . '.pdf';
            case 'Invoice':
                return trans('bt.invoice') . '_' . str_replace('/', '-', $document->number) . '.pdf';
            case 'Purchaseorder':
                return trans('bt.purchaseorder') . '_' . str_replace('/', '-', $document->number) . '.pdf';
        }
    }


    public static function batchprint($batch_type)
    {
        return trans('bt.batchprint') . '_' . ucfirst($batch_type) . '.pdf';
    }
}
