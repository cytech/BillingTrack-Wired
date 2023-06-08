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

use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Payments\Models\Payment;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;

class ProfitLossReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null, $includeProfitBasedOn = 'invoice_date')
    {
        $results = [
            'from_date'      => DateFormatter::format($fromDate),
            'to_date'        => DateFormatter::format($toDate),
            'income'         => 0,
            'total_expenses' => 0,
            'net_income'     => 0,
            'vendor_payments'=> 0,
            'expenses'       => [],
        ];

        $payments = Payment::select('payments.*')->whereHas('Invoice')
            ->join('documents', 'documents.id', '=', 'payments.invoice_id')
            ->with('invoice');

        if ($includeProfitBasedOn == 'invoice_date')
        {
            $payments->where('documents.document_date', '>=', $fromDate)->where('documents.document_date', '<=', $toDate);
        }
        elseif ($includeProfitBasedOn == 'payment_date')
        {
            $payments->dateRange($fromDate, $toDate);
        }

        if ($companyProfileId)
        {
            $payments->where('documents.company_profile_id', $companyProfileId);
        }

        $payments = $payments->get();

        foreach ($payments as $payment)
        {
            $results['income'] += $payment->amount / $payment->invoice->exchange_rate;
        }

        $vendorpayments = Payment::select('payments.*')->whereHas('Purchaseorder')
            ->join('documents', 'documents.id', '=', 'payments.invoice_id')
            ->with('purchaseorder');

        if ($includeProfitBasedOn == 'invoice_date')
        {
            $vendorpayments->where('documents.document_date', '>=', $fromDate)->where('documents.document_date', '<=', $toDate);
        }
        elseif ($includeProfitBasedOn == 'payment_date')
        {
            $vendorpayments->dateRange($fromDate, $toDate);
        }

        if ($companyProfileId)
        {
            $vendorpayments->where('documents.company_profile_id', $companyProfileId);
        }

        $vendorpayments = $vendorpayments->get();

        foreach ($vendorpayments as $vendorpayment)
        {
            $results['vendor_payments'] += $vendorpayment->amount / $vendorpayment->purchaseorder->exchange_rate;
        }

        $expenses = Expense::where('expense_date', '>=', $fromDate)->where('expense_date', '<=', $toDate)->with('category');

        if ($companyProfileId)
        {
            $expenses->where('company_profile_id', $companyProfileId);
        }

        $expenses = $expenses->get();

        foreach ($expenses as $expense)
        {
            if (isset($results['expenses'][$expense->category->name]))
            {
                $results['expenses'][$expense->category->name] += $expense->amount;
            }
            else
            {
                $results['expenses'][$expense->category->name] = $expense->amount;
            }

            $results['total_expenses'] += $expense->amount;
        }

        foreach ($results['expenses'] as $category => $amount)
        {
            $results['expenses'][$category] = CurrencyFormatter::format($amount);
        }

        $results['net_income']     = $results['income'] - $results['total_expenses'] - $results['vendor_payments'];
        $results['income']         = CurrencyFormatter::format($results['income']);
        $results['total_expenses'] = CurrencyFormatter::format($results['total_expenses']);
        $results['vendor_payments'] = CurrencyFormatter::format($results['vendor_payments']);

        ksort($results['expenses']);

        return $results;
    }
}
