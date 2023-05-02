<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'company_profile_id' => trans('bt.company_profile'),
            'client_name'        => trans('bt.client'),
            'client_id'          => trans('bt.client'),
            'user_id'            => trans('bt.user'),
            'summary'            => trans('bt.summary'),
            'document_date'         => trans('bt.date'),
            'action_date'             => trans('bt.due'),
            'number'             => trans('bt.invoice_number'),
            'document_status_id'    => trans('bt.status'),
            'exchange_rate'      => trans('bt.exchange_rate'),
            'template'           => trans('bt.template'),
            'group_id'           => trans('bt.group'),
            'items.*.name'       => trans('bt.name'),
            'items.*.quantity'   => trans('bt.quantity'),
            'items.*.price'      => trans('bt.price'),
        ];
    }

    public function rules()
    {
        return [
            'company_profile_id' => 'required|integer|exists:company_profiles,id',
            'client_name'        => 'required',
            'document_date'         => 'required',
            'user_id'            => 'required',
        ];
    }
}
