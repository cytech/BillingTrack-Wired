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

class Purchaseorder extends Document
{
    use HasParent;

    // HasParent function getMorphClass returns the parent class
    // overriding here to return the child class for morphMany relations
    public function getMorphClass(): string
    {
        return $this::class;
    }

    public function vendor()
    {
        return $this->belongsTo('BT\Modules\Vendors\Models\Vendor', 'client_id');
    }

    public function vendorAttachments()
    {
        $relationship = $this->morphMany('BT\Modules\Attachments\Models\Attachment', 'attachable');

        if ($this->status_text == 'paid')
        {
            $relationship->whereIn('vendor_visibility', [1, 2]);
        }
        else
        {
            $relationship->where('vendor_visibility', 1);
        }

        return $relationship;
    }
    public function scopeVendorId($query, $vendorId = null)
    {
        if ($vendorId)
        {
            $query->where('client_id', $vendorId);
        }

        return $query;
    }

    public function scopeReceived($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('received'));
    }

    public function scopePartial($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('partial'));
    }

    public function scopeSentOrPartial($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('sent'))
            ->orWhere('document_status_id', '=', DocumentStatuses::getStatusId('partial'));
    }

    public function scopePaid($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('paid'));
    }


}
