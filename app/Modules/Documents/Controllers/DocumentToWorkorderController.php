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
use BT\Modules\Groups\Models\Group;
use BT\Modules\Documents\Models\Document;
use BT\Support\ConvertToModule;
use BT\Modules\Documents\Requests\DocumentToWorkorderRequest;
use BT\Support\DateFormatter;

class DocumentToWorkorderController extends Controller
{
    private $documentToWorkorder;

    public function __construct(ConvertToModule $documentToWorkorder)
    {
        $this->documentToWorkorder = $documentToWorkorder;
    }

    public function create()
    {
        return view('documents._modal_document_to_workorder')
            ->with('document_id', request('document_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', Group::getList())
            ->with('user_id', auth()->user()->id)
            ->with('workorder_date', date('Y-m-d'));
    }

    public function store(DocumentToWorkorderRequest $request)
    {
        $document = Document::find($request->input('document_id'));

        $workorder = $this->documentToWorkorder->convert(
            $document,
            $request->input('workorder_date'),
            DateFormatter::incrementDateByDays($request->input('workorder_date'), config('bt.workordersExpireAfter')),
            $request->input('group_id'),
            'Workorder'
        );

        return response()->json(['redirectTo' => route('workorders.edit', ['id' => $workorder->id])], 200);
    }
}
