<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Reports\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStatementReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'from_date'   => trans('bt.from_date'),
            'to_date'     => trans('bt.to_date'),
            'client_name' => trans('bt.client'),
        ];
    }

    public function rules()
    {
        return [
            'from_date'   => 'required',
            'to_date'     => 'required',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    public function messages()
    {
        return [
            'client_id.required'   => trans('bt.client_not_found'),
        ];
    }
}
