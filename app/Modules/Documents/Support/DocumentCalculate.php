<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Support;

use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\DocumentAmount;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Documents\Models\DocumentItemAmount;
use BT\Modules\Payments\Models\Payment;
use BT\Support\Statuses\DocumentStatuses;

class DocumentCalculate
{
    public function calculate($document)
    {
        $documentItems = DocumentItem::select('document_items.*',
            'tax_rates_1.percent AS tax_rate_1_percent',
            'tax_rates_2.percent AS tax_rate_2_percent',
            'tax_rates_2.is_compound AS tax_rate_2_is_compound',
            'tax_rates_1.calculate_vat AS tax_rate_1_calculate_vat')
            ->leftJoin('tax_rates AS tax_rates_1', 'document_items.tax_rate_id', '=', 'tax_rates_1.id')
            ->leftJoin('tax_rates AS tax_rates_2', 'document_items.tax_rate_2_id', '=', 'tax_rates_2.id')
            ->where('document_id', $document->id)
            ->get();

        $totalPaid = Payment::where('invoice_id', $document->id)->sum('amount');

        $calculator = new DocumentCalculator;

        $calculator->setId($document->id);

        $calculator->setTotalPaid($totalPaid);

        $calculator->setDiscount($document->discount);

        if ($document->status_text == 'canceled') {
            $calculator->setIsCanceled(true);
        }

        foreach ($documentItems as $documentItem) {
            $taxRatePercent = ($documentItem->tax_rate_id) ? $documentItem->tax_rate_1_percent : 0;
            $taxRate2Percent = ($documentItem->tax_rate_2_id) ? $documentItem->tax_rate_2_percent : 0;
            $taxRate2IsCompound = ($documentItem->tax_rate_2_is_compound) ? 1 : 0;
            $taxRate1CalculateVat = ($documentItem->tax_rate_1_calculate_vat) ? 1 : 0;

            $calculator->addItem($documentItem->id, $documentItem->quantity, $documentItem->price, $taxRatePercent, $taxRate2Percent, $taxRate2IsCompound, $taxRate1CalculateVat);
        }

        $calculator->calculate();

        // Get the calculated values
        $calculatedItemAmounts = $calculator->getCalculatedItemAmounts();
        $calculatedAmount = $calculator->getCalculatedAmount();

        // Update the item amount records
        foreach ($calculatedItemAmounts as $calculatedItemAmount) {
            $documentItemAmount = DocumentItemAmount::firstOrNew(['item_id' => $calculatedItemAmount['item_id']]);
            $documentItemAmount->fill($calculatedItemAmount);
            $documentItemAmount->save();
        }

        // Update the overall document amount record
        $documentAmount = DocumentAmount::firstOrNew(['document_id' => $document->id]);
        $documentAmount->fill($calculatedAmount);
        $documentAmount->save();

        // Check to see if the invoice should be marked as paid.
        if ($calculatedAmount['total'] > 0 and $calculatedAmount['balance'] <= 0 and $document->status_text != 'canceled') {
            $document->document_status_id = DocumentStatuses::getStatusId('paid');
            $document->save();
        }

        // Check to see if the invoice was marked as paid but should no longer be.
        if ($calculatedAmount['total'] > 0 and $calculatedAmount['balance'] > 0 and $document->document_status_id == DocumentStatuses::getStatusId('paid')) {
            $document->document_status_id = DocumentStatuses::getStatusId('sent');
            $document->save();
        }
    }

    public function calculateAll($moduletype)
    {
        foreach ($moduletype::get() as $document) {
            $this->calculate($document);
        }
    }
}
