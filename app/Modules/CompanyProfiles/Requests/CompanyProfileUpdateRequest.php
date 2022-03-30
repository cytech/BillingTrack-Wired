<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\CompanyProfiles\Requests;

class CompanyProfileUpdateRequest extends CompanyProfileStoreRequest
{
    public function rules()
    {
        return ['company' => 'required|unique:company_profiles,company,' . $this->route('id')];
    }
}
