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
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Recurr\Exception\InvalidRRule;
use Recurr\Rule;
use Recurr\Transformer\TextTransformer;

class Schedule extends Model
{

    use SoftDeletes, SoftCascadeTrait, FormAccessible;

    protected $softCascade = ['occurrences', 'resources'];

//    protected $appends = ['text_trans', 'rule_start', 'formatted_date_trashed'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id'];

    protected $table = 'schedule';

    public $timestamps = true;


    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(ScheduleOccurrence::class, 'schedule_id', 'id');
    }

    public function occurrence(): HasOne
    {
        return $this->hasOne(ScheduleOccurrence::class, 'schedule_id', 'id');
    }

    public function latestOccurrence(): HasOne
    {
        return $this->occurrence()->latest('start_date');
    }

    public function firstOccurrence(): HasOne
    {
        return $this->occurrence()->oldest('start_date');
    }

    public function resources(): HasManyThrough
    {
        return $this->hasManyThrough(ScheduleResource::class, ScheduleOccurrence::class, 'schedule_id', 'occurrence_id', 'id', 'id');
    }

    public function resource(): HasOneThrough
    {
        return $this->hasOneThrough(ScheduleResource::class, ScheduleOccurrence::class, 'schedule_id', 'occurrence_id', 'id', 'id');
    }

    //getters
    public function getTextTransAttribute()
    {
        try {
            $rule = new Rule($this->rrule, new \DateTime());
        } catch (InvalidRRule $e) {
        }
        $textTransformer = new TextTransformer();
        return $textTransformer->transform($rule);
    }

    public function getRuleStartAttribute()
    {
        if ($this->rrule) {
            $rule = Rule::createFromString($this->rrule);
            return $rule->getStartDate()->format('Y-m-d H:i');
        }
    }

    public function getFormattedRuleStartAttribute()
    {
        if ($this->rrule) {
            $rule = Rule::createFromString($this->rrule);
            return DateFormatter::format($rule->getStartDate()->format('Y-m-d H:i'), true);
        }
    }

    public function formattedDateTrashed(): Attribute
    {
        return new Attribute(get: fn() => Carbon::parse($this->deleted_at)->format('Y-m-d H:i'));
    }

    //below for form model binding
    public function formStartDateAttribute()
    {
        return Carbon::parse($this->start_date)->format('Y-m-d H:i');
    }

    public function formEndDateAttribute()
    {
        return Carbon::parse($this->end_date)->format('Y-m-d H:i');
    }

    //scopes
    public function scopeWithOccurrences($query)
    {
        $query->leftjoin('schedule_occurrences', 'schedule.id', '=',
            'schedule_occurrences.schedule_id')->select('*', 'schedule.id as id', 'schedule_occurrences.id as oid');
    }

}
