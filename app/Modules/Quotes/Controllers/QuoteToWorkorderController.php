<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Quotes\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Quotes\Models\Quote;
use BT\Support\ConvertToModule;
use BT\Modules\Quotes\Requests\QuoteToWorkorderRequest;
use BT\Support\DateFormatter;

class QuoteToWorkorderController extends Controller
{
    private $quoteToWorkorder;

    public function __construct(ConvertToModule $quoteToWorkorder)
    {
        $this->quoteToWorkorder = $quoteToWorkorder;
    }

    public function create()
    {
        return view('quotes._modal_quote_to_workorder')
            ->with('quote_id', request('quote_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', Group::getList())
            ->with('user_id', auth()->user()->id)
            ->with('workorder_date', date('Y-m-d'));
    }

    public function store(QuoteToWorkorderRequest $request)
    {
        $quote = Quote::find($request->input('quote_id'));

        $workorder = $this->quoteToWorkorder->convert(
            $quote,
            $request->input('workorder_date'),
            DateFormatter::incrementDateByDays($request->input('workorder_date'), config('bt.workordersExpireAfter')),
            $request->input('group_id'),
            'Workorder'
        );

        return response()->json(['redirectTo' => route('workorders.edit', ['id' => $workorder->id])], 200);
    }
}
