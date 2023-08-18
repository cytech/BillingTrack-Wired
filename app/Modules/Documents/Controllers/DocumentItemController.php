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
use BT\Modules\Documents\Models\DocumentItem;

class DocumentItemController extends Controller
{
    public function delete()
    {
        $documentItem = DocumentItem::find(request('id'));

        if ($documentItem->document->moduleType == 'Invoice' && config('bt.updateInvProductsDefault') && $documentItem->is_tracked) {
            $documentItem->product->increment('numstock', $documentItem->quantity);
        }

        try {
            $documentItem->delete();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => trans('bt.record_successfully_trashed')], 200);
    }
}
