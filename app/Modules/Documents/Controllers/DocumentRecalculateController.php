<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Support\DocumentCalculate;

class DocumentRecalculateController extends Controller
{
    private $documentCalculate;

    public function __construct(DocumentCalculate $documentCalculate)
    {
        $this->documentCalculate = $documentCalculate;
    }

    public function recalculate()
    {
        try {
            $this->documentCalculate->calculateAll();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => trans('bt.recalculation_complete'),
        ], 200);
    }
}
