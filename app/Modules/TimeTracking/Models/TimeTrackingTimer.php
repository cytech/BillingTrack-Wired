<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\TimeTracking\Models;

use BT\Support\DateFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTrackingTimer extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];

    protected $table = 'time_tracking_timers';

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
    }

    public function formattedBilled(): Attribute
    {
        return new Attribute(get: fn () => ($this->billed) ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedEndAt(): Attribute
    {
        if ($this->end_at != null) {
            return new Attribute(get: fn () => DateFormatter::format($this->end_at, true));
        }

        return new Attribute(get: fn () => '');
    }

    public function formattedHours(): Attribute
    {
        return new Attribute(get: fn () => NumberFormatter::format($this->hours));
    }

    public function formattedStartAt(): Attribute
    {
        return new Attribute(get: fn () => DateFormatter::format($this->start_at, true));
    }

    public function hours(): Attribute
    {
        if (! $this->formatted_end_at) {
            return new Attribute(get: fn () => '');
        }

        return new Attribute(get: fn () => $this->attributes['hours']);
    }
}
