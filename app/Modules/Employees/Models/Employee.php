<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Employees\Models;

use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    /**
     * Guarded properties
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'employees';

    //    protected $appends = ['formatted_billing_rate'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(EmployeeType::class);
    }

    //mutators
    public function firstName(): Attribute
    {
        return new Attribute(set: fn ($value) => $this->first_name = ucfirst($value));
    }

    public function lastName(): Attribute
    {
        return new Attribute(set: fn ($value) => $this->last_name = ucfirst($value));
    }

    public function fullName(): Attribute
    {
        return new Attribute(set: fn ($value) => $this->full_name = $this->first_name.' '.$this->last_name);
    }

    public function shortName(): Attribute
    {
        return new Attribute(set: fn ($value) => $this->short_name = $this->first_name.' '.substr($this->last_name, 0, 1).'.');
    }

    public function formattedShortName(): Attribute
    {
        if ($this->driver == 1) {
            return new Attribute(get: fn () => '<span style = "color:blue">'.$this->short_name.'</span>');
        }

        return new Attribute(get: fn () => $this->short_name);
    }

    //getters
    public function formattedBillingRate(): Attribute
    {
        return new Attribute(get: fn () => CurrencyFormatter::format($this->billing_rate));
    }

    public function formattedTermDate(): Attribute
    {
        if (! is_null($this->term_date)) {
            return new Attribute(get: fn () => DateFormatter::format($this->term_date));
        }

        return Attribute::get(fn () => null);
    }

    public function formattedSchedule(): Attribute
    {
        return new Attribute(get: fn () => $this->schedule ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedActive(): Attribute
    {
        return new Attribute(get: fn () => $this->active ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedDriver(): Attribute
    {
        return new Attribute(get: fn () => $this->driver ? trans('bt.yes') : trans('bt.no'));
    }

    public function scopeStatus($query, $status)
    {
        if ($status == 'active') {
            $query->where('active', 1);
        } elseif ($status == 'inactive') {
            $query->where('active', 0);
        }

        return $query;
    }
}
