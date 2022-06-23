<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Employees\Models;

use BT\Modules\Scheduler\Models\ScheduleResource;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

	protected $table = 'employees';

//    protected $appends = ['formatted_billing_rate'];

    public function workorderitem()
    {
        return $this->belongsTo('BT\Modules\Workorders\Models\WorkorderItem','resource_id', 'id')
            ->where('resource_table','=','employees');
    }

    public function scheduleresource()
    {
        return $this->belongsTo(ScheduleResource::class,'resource_id', 'id')
            ->where('resource_table','=','employees');
    }

    public function type()
    {
        return $this->belongsTo('BT\Modules\Employees\Models\EmployeeType');
    }

    //mutators
    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = ucfirst($value);
    }

    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucfirst($value);
    }

    public function setFullNameAttribute(){
        $this->attributes['full_name'] = $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public function setShortNameAttribute($value){
        $this->attributes['short_name'] = $this->attributes['first_name'] . ' ' . substr($this->attributes['last_name'],0,1) . '.';
    }
    //getters
    public function getFormattedBillingRateAttribute()
    {
        return CurrencyFormatter::format($this->attributes['billing_rate']);
    }

    public function getFormattedTermDateAttribute()
    {
        if (!is_null($this->attributes['term_date'])) {
            return DateFormatter::format($this->attributes['term_date']);
        }
    }

    public function getFormattedScheduleAttribute(){
        return $this->schedule ? trans('bt.yes') : trans('bt.no');
    }

    public function getFormattedActiveAttribute(){
        return $this->active ? trans('bt.yes') : trans('bt.no');
    }

    public function getFormattedDriverAttribute(){
        return $this->driver ? trans('bt.yes') : trans('bt.no');
    }


    public function scopeStatus($query, $status)
    {
        if ($status == 'active')
        {
            $query->where('active', 1);
        }
        elseif ($status == 'inactive')
        {
            $query->where('active', 0);
        }

        return $query;
    }


}
