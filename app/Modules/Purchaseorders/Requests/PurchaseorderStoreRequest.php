<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Purchaseorders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseorderStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'company_profile_id' => trans('bt.company_profile'),
            'vendor_name'        => trans('bt.vendor'),
            'vendor_id'          => trans('bt.vendor'),
            'user_id'            => trans('bt.user'),
            'summary'            => trans('bt.summary'),
            'purchaseorder_date'       => trans('bt.date'),
            'due_at'             => trans('bt.due'),
            'number'             => trans('bt.purchaseorder_number'),
            'purchaseorder_status_id'  => trans('bt.status'),
            'exchange_rate'      => trans('bt.exchange_rate'),
            'template'           => trans('bt.template'),
            'group_id'           => trans('bt.group'),
            'items.*.name'       => trans('bt.name'),
            'items.*.quantity'   => trans('bt.quantity'),
            'items.*.cost'      => trans('bt.product_cost'),
        ];
    }

    public function rules()
    {
        return [
            'company_profile_id' => 'required|integer|exists:company_profiles,id',
            'vendor_name'        => 'required',
            'purchaseorder_date'       => 'required',
            'user_id'            => 'required',
        ];
    }
}
