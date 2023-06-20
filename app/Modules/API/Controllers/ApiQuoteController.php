<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\API\Controllers;

use BT\Events\DocumentModified;
use BT\Modules\API\Requests\APIQuoteItemRequest;
use BT\Modules\API\Requests\APIQuoteStoreRequest;
use BT\Modules\Clients\Models\Client;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Users\Models\User;

class ApiQuoteController extends ApiController
{
    public function lists()
    {
        $quotes = Quote::select('quotes.*')
            ->with(['items.amount', 'client', 'amount', 'currency'])
            ->status(request('status'))
            ->paginate(config('bt.resultsPerPage'));

        return response()->json($quotes);
    }

    public function show()
    {
        return response()->json(Quote::with(['items.amount', 'client', 'amount', 'currency'])->find(request('id')));
    }

    public function store(APIQuoteStoreRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        $input['user_id'] = User::where('client_id', 0)->where('api_public_key', $request->input('key'))->first()->id;

        $input['client_id'] = Client::firstOrCreateByName($request->client_id, $request->client_name)->id;

        unset($input['client_name']);

        return response()->json(Quote::create($input));
    }

    public function addItem(APIQuoteItemRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        DocumentItem::create($input);
        $quote = Quote::find(request('document_id'));
        event(new DocumentModified($quote));
    }

    public function delete()
    {
        $validator = $this->validator->make(['id' => request('id')], ['id' => 'required']);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        if (Quote::find(request('id')))
        {
            Quote::destroy(request('id'));

            return response(200);
        }

        return response()->json([trans('bt.record_not_found')], 400);
    }
}
