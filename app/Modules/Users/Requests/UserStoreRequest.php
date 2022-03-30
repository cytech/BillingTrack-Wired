<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'email'    => trans('bt.email'),
            'password' => trans('bt.password'),
            'client_name'     => trans('bt.name'),
            'name'     => trans('bt.name'),
        ];
    }

    public function rules()
    {
        return [
            'email'    => 'sometimes|required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'client_name'     => 'sometimes|required',
            'name'     => 'sometimes|required',
        ];
    }
}
