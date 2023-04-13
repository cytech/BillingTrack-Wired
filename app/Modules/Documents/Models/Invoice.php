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

use BT\Support\Statuses\DocumentStatuses;
use Parental\HasParent;

class Invoice extends Document
{
    use HasParent;

    // HasParent function getMorphClass returns the parent class
    // overriding here to return the child class for morphMany relations
    public function getMorphClass(): string
    {
        return $this::class;
    }

    public function expense()
    {
        return $this->hasOne('BT\Modules\Expenses\Models\Expense');
    }
    public function payments()
    {
        return $this->hasMany('BT\Modules\Payments\Models\Payment');
    }

    public function transactions()
    {
        return $this->hasMany('BT\Modules\Merchant\Models\InvoiceTransaction');
    }

    public function getIsOverdueAttribute()
    {
        // Only invoices in Sent status, with a balance qualify to be overdue
        if ($this->attributes['action_date'] < date('Y-m-d')
            and $this->attributes['document_status_id'] == DocumentStatuses::getStatusId('sent')
            and $this->amount->balance <> 0)
            return 1;

        return 0;
    }

    //scopes
    public function scopePaid($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('paid'));
    }
}
