<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Scheduler\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Support\DateFormatter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleOccurrence extends Model
{
    use SoftDeletes, SoftCascadeTrait;

    protected $softCascade = ['resources'];

    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $table = 'schedule_occurrences';

    protected $guarded = ['id'];

    protected $casts = ['start_date' => 'datetime', 'end_date' => 'datetime', 'reminder_date' => 'datetime', 'deleted_at' => 'datetime'];

    //	protected $appends = ['formatted_start_date', 'formatted_end_date'];

    public static function reminderinterval()
    {
        return [
            'none' => __('bt.no_reminder'),
            'minutes' => __('bt.minutes'),
            'hours' => __('bt.hours'),
            'days' => __('bt.days'),
            'weeks' => __('bt.weeks'),
        ];
    }

    public static function reminderDate($num, $interval, $startdate)
    {
        $date = Carbon::parse($startdate);

        if ($interval != 'none') {
            return $date->subtract($num, $interval);
        }

        return null;
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(ScheduleResource::class, 'occurrence_id', 'id');
    }

    public function resource(): HasOne
    {
        return $this->hasOne(ScheduleResource::class, 'occurrence_id', 'id');
    }

    //getters

    public function formattedStartDate(): Attribute
    {
        return new Attribute(get: fn () => DateFormatter::format($this->start_date, true));
    }

    public function formattedEndDate(): Attribute
    {
        return new Attribute(get: fn () => DateFormatter::format($this->end_date, true));
    }
}
