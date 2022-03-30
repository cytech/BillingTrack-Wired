<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name'        => trans('bt.name'),
            'unique_name' => trans('bt.unique_name'),
            'email'       => trans('bt.email'),
        ];
    }

    public function prepareForValidation()
    {
        $request = $this->all();

        $request['email'] = $this->input('client_email', $this->input('email', ''));

        unset($request['client_email']);

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'name'        => 'required',
            'unique_name' => 'required_with:name|unique:clients',
            'email'       => 'email',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'unique_name.unique'   => 'The :attribute has already been taken.<br/> (Duplicate may exist in trash)',
        ];
    }
}
