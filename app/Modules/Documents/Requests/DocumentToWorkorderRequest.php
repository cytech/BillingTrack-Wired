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

class DocumentToWorkorderRequest extends DocumentStoreRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required',
            'document_date' => 'required',
            'group_id' => 'required',
        ];
    }
}
