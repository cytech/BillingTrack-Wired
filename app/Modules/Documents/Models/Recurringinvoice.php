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

use BT\Support\DateFormatter;
use BT\Support\Statuses\DocumentStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Parental\HasParent;

class Recurringinvoice extends Document
{
    use HasParent;

    // HasParent function getMorphClass returns the parent class
    // overriding here to return the child class for morphMany relations
    public function getMorphClass(): string
    {
        return $this::class;
    }

    //relations

    public function formattedNextDate(): Attribute
    {
        if ($this->next_date != '0000-00-00') {
            return new Attribute(get: fn () => DateFormatter::format($this->next_date));
        }

        return new Attribute(get: fn () => '');
    }

    public function formattedStopDate(): Attribute
    {
        if ($this->stop_date != '0000-00-00') {
            return new Attribute(get: fn () => DateFormatter::format($this->stop_date));
        }

        return new Attribute(get: fn () => '');
    }

    //scopes
    public function scopeRecurNow($query)
    {
        $query->where('document_status_id', DocumentStatuses::getStatusId('active'));
        $query->where('next_date', '<>', '0000-00-00');
        $query->where('next_date', '<=', date('Y-m-d'));
        $query->where(function ($q) {
            $q->where('stop_date', '0000-00-00');
            $q->orWhere('next_date', '<=', DB::raw('stop_date'));
        });

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('document_status_id', '<>', DocumentStatuses::getStatusId('active'));
    }

    public function scopeInactive($query)
    {
        return $query->where('document_status_id', '<>', DocumentStatuses::getStatusId('inactive'));
    }

    public function scopeStatus($query, $status = null)
    {
        return match ($status) {
            'active' => $query->active(),
            'inactive' => $query->inactive(),
            default => $query,
        };

    }
}
