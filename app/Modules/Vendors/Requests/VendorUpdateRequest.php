<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Vendors\Requests;

class VendorUpdateRequest extends VendorStoreRequest
{
    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }
}
