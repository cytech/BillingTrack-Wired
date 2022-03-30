<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\RecurringInvoices\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\RecurringInvoices\Support\RecurringInvoiceCalculate;

class RecurringInvoiceRecalculateController extends Controller
{
    private $recurringInvoiceCalculate;

    public function __construct(RecurringInvoiceCalculate $recurringInvoiceCalculate)
    {
        $this->recurringInvoiceCalculate = $recurringInvoiceCalculate;
    }

    public function recalculate()
    {
        try {
            $this->recurringInvoiceCalculate->calculateAll();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => trans('bt.recalculation_complete')], 200);
    }
}
