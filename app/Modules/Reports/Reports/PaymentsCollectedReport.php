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

use BT\Modules\Payments\Models\Payment;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;

class PaymentsCollectedReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date'   => DateFormatter::format($toDate),
            'payments'  => [],
            'total'     => 0,
        ];

        $payments = Payment::select('payments.*')
            ->whereHas('invoice')->orWhere('payments.invoice_id', 0) // 0 = deleted invoice
            ->dateRange($fromDate, $toDate);

        if ($companyProfileId)
        {
            $payments->where('documents.company_profile_id', $companyProfileId);
        }

        $payments = $payments->get();

        foreach ($payments as $payment)
        {
            $results['payments'][] = [
                'client_name'    => $payment->client->name,
                'invoice_number' => $payment->invoice->number ?? __('bt.deleted'),
                'payment_method' => $payment->paymentMethod->name ?? '',
                'note'           => $payment->note,
                'date'           => $payment->formatted_paid_at,
                'amount'         => $payment->invoice ? CurrencyFormatter::format($payment->amount / $payment->invoice->exchange_rate) :
                                    CurrencyFormatter::format($payment->amount ),
            ];

            $results['total'] += $payment->invoice ? $payment->amount / $payment->invoice->exchange_rate :
                                 $payment->amount;
        }

        $results['total'] = CurrencyFormatter::format($results['total']);

        return $results;
    }
}
