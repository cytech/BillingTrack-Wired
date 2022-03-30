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

use BT\Modules\Payments\Models\Payment;

class Payments implements SourceInterface
{
    public function getResults($params = [])
    {
        $payment = Payment::select('invoices.number', 'payments.paid_at', 'payments.amount',
            'payment_methods.name AS payment_method', 'payments.note')
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payment_method_id')
            ->orderBy('invoices.number');

        return $payment->get()->makeHidden(['formatted_paid_at','formatted_amount'])->toArray();
    }
}
