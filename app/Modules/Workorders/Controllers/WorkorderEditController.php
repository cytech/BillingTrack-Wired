<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Workorders\Controllers;

use BT\Events\WorkorderModified;
use BT\Http\Controllers\Controller;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\ItemLookups\Models\ItemLookup;
use BT\Modules\Workorders\Models\Workorder;
use BT\Modules\Workorders\Models\WorkorderItem;
use BT\Modules\Workorders\Support\WorkorderTemplates;
use BT\Modules\Workorders\Requests\WorkorderUpdateRequest;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Support\Statuses\WorkorderStatuses;
use BT\Traits\ReturnUrl;

class WorkorderEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $this->setPreviousUrl();

        $workorder = Workorder::with(['workorderItems.amount.item.workorder.currency'])->find($id);

        return view('workorders.edit')
            ->with('workorder', $workorder)
            ->with('statuses', WorkorderStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('workorders')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', WorkorderTemplates::lists())
            ->with('itemCount', count($workorder->workorderItems));
    }

    public function update(WorkorderUpdateRequest $request, $id)
    {
        $input = request()->except(['items', 'custom', 'apply_exchange_rate']);

        // Save the workorder.
        $workorder = Workorder::find($id);
        $workorder->fill($input);
        $workorder->save();

        // Save the custom fields.
        $workorder->custom->update($request->input('custom', []));

        // Save the items.
        foreach ($request->input('items') as $item) {
            $item['apply_exchange_rate'] = $request->input('apply_exchange_rate');

            if (!isset($item['id']) or (!$item['id'])) {
                //if item_lookup and item_lookup has resource, remap item to resource
                if ($item['resource_table'] == 'item_lookups'){
                    $il = ItemLookup::find($item['resource_id']);
                    if ($il->resource_table){
                        $item['resource_table'] = $il->resource_table;
                        $item['resource_id'] = $il->resource_id;
                    }
                }
                WorkorderItem::create($item);
            } else {
                $workorderItem = WorkorderItem::find($item['id']);
                $workorderItem->fill($item);
                $workorderItem->save();
            }
        }

        event(new WorkorderModified($workorder));

        return response()->json(['success' => true], 200);
    }

    public function refreshEdit($id)
    {
        $workorder = Workorder::with(['items.amount.item.workorder.currency'])->find($id);

        return view('workorders._edit')
            ->with('workorder', $workorder)
            ->with('statuses', WorkorderStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('workorders')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', WorkorderTemplates::lists())
            ->with('itemCount', count($workorder->workorderItems));
    }

    public function refreshTotals()
    {
        return view('workorders._edit_totals')
            ->with('workorder', Workorder::with(['items.amount.item.workorder.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('workorders._edit_to')
            ->with('workorder', Workorder::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('workorders._edit_from')
            ->with('workorder', Workorder::find(request('id')));
    }

//    public function updateClient()
//    {
//        Workorder::where('id', request('id'))->update(['client_id' => request('client_id')]);
//    }

    public function updateCompanyProfile()
    {
        Workorder::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}
