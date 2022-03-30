<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace BT\Modules\Workorders\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Workorders\Models\WorkorderItem;

class WorkorderItemController extends Controller
{
    public function delete()
    {
        try {
            WorkorderItem::destroy(request('id'));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
        return response()->json(['success' => true, 'message' => trans('bt.record_successfully_trashed')], 200);
    }
}
