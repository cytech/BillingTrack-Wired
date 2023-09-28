<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Payments\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\PaymentMethods\Models\PaymentMethod;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\Payments\Requests\PaymentRequest;

class PaymentController extends Controller
{
    public function index()
    {
        $statuses = ['1' => __('bt.client_payments'), '2' => __('bt.vendor_payments')];
        $modulefullname = Payment::class;

        return view('payments.index', compact('statuses', 'modulefullname'));
    }

    public function edit($id)
    {
        $payment = Payment::find($id);

        return view('payments.form')
            ->with('payment', $payment)
            ->with('paymentMethods', PaymentMethod::getList())
            ->with('invoice', $payment->invoice)
            ->with('customFields', CustomField::forTable('payments')->get());
    }

    public function update(PaymentRequest $request, $id)
    {
        $input = $request->except('custom');

        $payment = Payment::find($id);
        $payment->fill($input);
        $payment->save();

        $payment->custom->update($request->input('custom', []));

        return redirect()->route('payments.index', ['status' => 1])
            ->with('alertInfo', trans('bt.record_successfully_updated'));
    }

    public function delete($id)
    {
        Payment::destroy($id);

        return redirect()->route('payments.index', ['status' => 1])
            ->with('alert', trans('bt.record_successfully_trashed'));
    }

    public function bulkDelete()
    {
        Payment::destroy(request('ids'));

        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);
    }
}
