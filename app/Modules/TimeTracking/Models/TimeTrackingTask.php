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

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Documents\Models\Invoice;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TimeTrackingTask extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $softCascade = ['timers'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $table = 'time_tracking_tasks';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activeTimer(): HasOne
    {
        return $this->hasOne(TimeTrackingTimer::class)->where('time_tracking_timers.end_at', null);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class)->withTrashed();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(TimeTrackingProject::class, 'time_tracking_project_id');
    }

    public function timers(): HasMany
    {
        return $this->hasMany(TimeTrackingTimer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedHours(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->hours));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGetSelect($query)
    {
        return $query->select(
            'time_tracking_tasks.*',
            DB::raw('(' . $this->getHoursSql() . ') AS hours')
        );
    }

    public function scopeBilled($query)
    {
        return $query->where('billed', 1);
    }

    public function scopeUnbilled($query)
    {
        return $query->where('billed', 0);
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->whereHas('timers', function ($q) use ($fromDate, $toDate) {
            $q->where('start_at', '>=', $fromDate)->where('start_at', '<=', $toDate);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SQL
    |--------------------------------------------------------------------------
    */

    private function getHoursSql()
    {
        return DB::table('time_tracking_timers')
            ->selectRaw('IFNULL(SUM(hours), 0.00)')
            ->where('time_tracking_timers.time_tracking_task_id', '=', DB::raw(DB::getTablePrefix() . 'time_tracking_tasks.id'))
            ->whereNull('deleted_at')
            ->toSql();
    }
}
