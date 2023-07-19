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

use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\TimeTracking\Models\TimeTrackingTask;
use BT\Support\Statuses\DocumentStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    //relations
    public function workorder(): BelongsTo
    {
        return $this->belongsTo(Workorder::class, 'id', 'invoice_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'id', 'invoice_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'invoice_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }

    public function timetrackingtasks(): HasMany
    {
        return $this->hasMany(TimeTrackingTask::class, 'invoice_id', 'id');
    }

    // accessors
    public function isPayable(): Attribute
    {
        return new Attribute(get: fn() => $this->status_text <> 'canceled' and $this->amount->balance > 0);
    }

    public function getIsOverdueAttribute()
    {
        // Only invoices in Sent status, with a balance qualify to be overdue
        if ($this->action_date < date('Y-m-d')
            and $this->document_status_id == DocumentStatuses::getStatusId('sent')
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
