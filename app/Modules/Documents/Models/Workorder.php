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
use Parental\HasParent;

class Workorder extends Document
{
    use HasParent;

    protected $casts = ['job_date' => 'datetime'];

    public function getFormattedJobDateAttribute()
    {
        return DateFormatter::format($this->attributes['job_date']);

    }

    public function getFormattedStartTimeAttribute()
    {
        return DateFormatter::formattime($this->attributes['start_time']);

    }

    public function getFormattedEndTimeAttribute()
    {
        return DateFormatter::formattime($this->attributes['end_time']);

    }

    public function getFormattedJobLengthAttribute()
    {
        $datetime1 = new \DateTime($this->attributes['start_time']);
        $datetime2 = new \DateTime($this->attributes['end_time']);
        $interval = $datetime1->diff($datetime2);
        return $interval->h+$interval->i/60;//return decimal hours

    }

}
