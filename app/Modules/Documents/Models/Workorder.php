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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Parental\HasParent;

class Workorder extends Document
{
    use HasParent;

    // HasParent function getMorphClass returns the parent class
    // overriding here to return the child class for morphMany relations
    public function getMorphClass(): string
    {
        return $this::class;
    }
    public function invoice() :HasOne
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id')->withTrashed();
    }

    public function formattedJobDate(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->job_date));
    }

    public function formattedStartTime(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::formattime($this->start_time));
    }

    public function formattedEndTime(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::formattime($this->end_time));
    }

    public function formattedJobLength(): Attribute
    {
        $datetime1 = new \DateTime($this->start_time);
        $datetime2 = new \DateTime($this->end_time);
        $interval = $datetime1->diff($datetime2);
        return new Attribute(get: fn() => $interval->h + $interval->i / 60);//return decimal hours
    }

}
