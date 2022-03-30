<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Vendors\Controllers;

use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\PaymentTerms\Models\PaymentTerm;
use BT\Modules\Vendors\Models\Vendor;
use BT\Http\Controllers\Controller;
use BT\Modules\Vendors\Requests\VendorStoreRequest;
use BT\Modules\Vendors\Requests\VendorUpdateRequest;
use BT\Traits\ReturnUrl;


class VendorController extends Controller
{
    use ReturnUrl;

    /**
     * Display a listing of the product.
     *
     */
    public function index()
    {
        $this->setReturnUrl();

        $status = (request('status')) ?: 'all';

        return view('vendors.index', ['status' => $status]);
    }

    /**
     * Show the form for creating a new product.
     *
     */
    public function create()
    {
        $payment_terms = PaymentTerm::pluck('name', 'id');

        return view('vendors.form', compact('payment_terms'))
            ->with('editMode', false)
            ->with('customFields', CustomField::forTable('vendors')->get())
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Store a newly created product in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VendorStoreRequest $request)
    {
        $vendor = Vendor::create($request->except('custom'));

        $vendor->custom->update($request->get('custom', []));

        return redirect()->route('vendors.show', [$vendor->id])
            ->with('alertInfo', trans('bt.record_successfully_created'));
    }

    /**
     * Display the specified product.
     *
     * @param int $id
     */
    public function show($vendorId)
    {
        $vendor = Vendor::getSelect()->find($vendorId);

        $purchaseorders = $vendor->purchaseorders()
            ->with(['vendor', 'activities', 'amount.purchaseorder.currency'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(config('bt.resultsPerPage'))->get();


        return view('vendors.view')
            ->with('vendor', $vendor)
            ->with('purchaseorders', $purchaseorders)
            ->with('customFields', CustomField::forTable('vendors')->get())
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param int $id
     */
    public function edit($vendorId)
    {
        $vendor = Vendor::getSelect()->with(['custom'])->find($vendorId);
        $payment_terms = PaymentTerm::pluck('name', 'id');

        return view('vendors.form', compact('payment_terms'))
            ->with('editMode', true)
            ->with('vendor', $vendor)
            ->with('customFields', CustomField::forTable('vendors')->get())
            ->with('returnUrl', $this->getReturnUrl());
    }

    /**
     * Update the specified product in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VendorUpdateRequest $request, $id)
    {
        $vendor = Vendor::find($id);
        $vendor->fill($request->except('custom'));
        $vendor->save();

        $vendor->custom->update($request->get('custom', []));

        return redirect()->route('vendors.show', [$id])
            ->with('alertInfo', trans('bt.record_successfully_updated'));
    }

    /**
     * Remove the specified product from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Vendor::destroy($id);

        return redirect()->route('vendors.index')
            ->with('alert', trans('bt.record_successfully_trashed'));
    }

    public function ajaxLookup()
    {
        $vendors = Vendor::select('id', 'name')
            ->where('active', 1)
            ->where('name', 'like', '%' . request('term') . '%')
            ->orderBy('name')
            ->get();

        $list = [];

        foreach ($vendors as $vendor) {
            $list[] = ['id' => $vendor->id, 'name' => $vendor->name];
        }

        return json_encode($list);
    }

    public function ajaxModalEdit()
    {
        return view('vendors._modal_edit')
            ->with('editMode', true)
            ->with('vendor', Vendor::getSelect()->with(['custom'])->find(request('vendor_id')))
            ->with('refreshToRoute', request('refresh_to_route'))
            ->with('id', request('id'))
            ->with('customFields', CustomField::forTable('vendors')->get())
            ->with('payment_terms', PaymentTerm::pluck('name', 'id'));
    }

    public function ajaxModalUpdate(VendorUpdateRequest $request, $id)
    {
        $vendor = Vendor::find($id);
        $vendor->fill($request->except('custom'));
        $vendor->save();

        $vendor->custom->update($request->get('custom', []));

        return response()->json(['success' => true], 200);
    }


}
