<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace BT\Modules\Scheduler\Models;

use BT\Modules\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleResource extends Model {

    use SoftDeletes;

    protected $table = 'schedule_resources';

    public $timestamps = true;

	protected $guarded = ['id'];

    protected $casts = ['deleted_at' => 'datetime'];

    public function occurrence()
    {
        return$this->belongsTo(ScheduleOccurrence::class, 'occurrence_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'id', 'resource_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'resource_id');
    }

}
