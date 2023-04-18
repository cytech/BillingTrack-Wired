<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace BT\Modules\Scheduler\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ScheduleReminderLegacy extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = ['reminder_date' => 'datetime', 'deleted_at' => 'datetime'];

    protected $table = 'schedule_reminders';

    //relations
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    //accessors
    public function reminderDate(): Attribute
    {
        return new Attribute(get: fn() => Carbon::parse($this->attributes['reminder_date'])->format('Y-m-d H:i'));
    }
}
