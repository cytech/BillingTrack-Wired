<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Models;

use Parental\HasParent;

class Quote extends Document
{
    use HasParent;

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id')->withTrashed();
    }

    public function workorder()
    {
        return $this->hasOne(Workorder::class, 'id', 'workorder_id')->withTrashed();
    }
}