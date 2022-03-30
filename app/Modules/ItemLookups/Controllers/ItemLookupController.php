<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\ItemLookups\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\ItemLookups\Models\ItemLookup;
use BT\Modules\ItemLookups\Requests\ItemLookupRequest;
use BT\Modules\TaxRates\Models\TaxRate;

class ItemLookupController extends Controller
{
    public function index()
    {
        return view('item_lookups.index');
    }

    public function create()
    {
        return view('item_lookups.form')
            ->with('editMode', false)
            ->with('taxRates', TaxRate::getList());
    }

    public function store(ItemLookupRequest $request)
    {
        ItemLookup::create($request->all());

        return redirect()->route('itemLookups.index')
            ->with('alertSuccess', trans('bt.record_successfully_created'));
    }

    public function edit($id)
    {
        $itemLookup = ItemLookup::find($id);

        return view('item_lookups.form')
            ->with('editMode', true)
            ->with('itemLookup', $itemLookup)
            ->with('taxRates', TaxRate::getList());
    }

    public function update(ItemLookupRequest $request, $id)
    {
        $itemLookup = ItemLookup::find($id);

        $itemLookup->fill($request->all());

        $itemLookup->save();

        return redirect()->route('itemLookups.index')
            ->with('alertInfo', trans('bt.record_successfully_updated'));
    }

    public function delete($id)
    {
        ItemLookup::destroy($id);

        return redirect()->route('itemLookups.index')
            ->with('alert', trans('bt.record_successfully_deleted'));
    }
}
