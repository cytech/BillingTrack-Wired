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

use Illuminate\Database\Eloquent\Relations\HasOne;
use Parental\HasParent;

class Quote extends Document
{
    use HasParent;

    // HasParent function getMorphClass returns the parent class
    // overriding here to return the child class for morphMany relations
    public function getMorphClass(): string
    {
        return $this::class;
    }
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id')->withTrashed();
    }

    public function workorder(): HasOne
    {
        return $this->hasOne(Workorder::class, 'id', 'workorder_id')->withTrashed();
    }
}
