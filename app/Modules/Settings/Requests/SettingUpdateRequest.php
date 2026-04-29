<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Settings\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'setting.invoicesDueAfter' => trans('bt.invoices_due_after'),
            'setting.quotesExpireAfter' => trans('bt.quotes_expire_after'),
            'setting.workordersExpireAfter' => trans('bt.workorders_expire_after'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'setting.invoicesDueAfter' => 'required|numeric',
            'setting.quotesExpireAfter' => 'required|numeric',
            'setting.workordersExpireAfter' => 'required|numeric',
        ];

        foreach (config('bt.settingValidationRules') as $settingValidationRules) {
            $rules = array_merge($rules, $settingValidationRules['rules']);
        }

        return $rules;
    }
}
