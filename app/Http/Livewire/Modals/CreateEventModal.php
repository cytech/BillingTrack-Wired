<?php

namespace BT\Http\Livewire\Modals;

use BT\Modules\Employees\Models\Employee;
use BT\Modules\Scheduler\Models\Category;
use BT\Modules\Scheduler\Models\Schedule;
use BT\Modules\Scheduler\Models\ScheduleOccurrence;
use BT\Modules\Scheduler\Models\ScheduleResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateEventModal extends Component
{
    public $title, $location, $description, $categories, $category_id;
    public $module, $start_date, $end_date, $reminder_qty = 0, $reminder_interval, $reminder_interval_id = 'none';
    public $resource_id, $resource_name, $fromcalendar;

    protected $listeners = ['resource_idUpdated'   => 'setResourceId',
                            'descriptionUpdated' => 'setResourceName',];

    protected $rules = [
        'title'      => 'required_without:resource_id',
        'resource_id'  => 'required_without:title',
        'start_date' => 'required',
        'end_date'   => 'required|after:start_date',
    ];

    // Schedule array (model from datatable thru modals ends up array), fromcalendar, date from fullcalendar
    public function mount($module = null, $fromcalendar = null, $date = null)
    {
        $this->categories = Category::pluck('name', 'id');
        $this->category_id = 2; //default to general appointment
        $this->reminder_interval = ScheduleOccurrence::reminderinterval();
        if ($date) { //date passed from fullcalendar dateclick
            $this->start_date = Carbon::parse($date)->format('Y-m-d') . ' 08:00:00';
            $this->end_date = Carbon::parse($date)->format('Y-m-d') . ' 09:00:00';
        } else {
            $this->start_date = date('Y-m-d') . ' 08:00:00';
            $this->end_date = date('Y-m-d') . ' 09:00:00';
        }
        if ($module) { // module passed from event edit or fullcalendar eventclick
            $this->getModule($module);
        }
        if ($fromcalendar) { // for return redirect
            $this->fromcalendar = true;
        }
    }

    public function getModule($module)
    {
        $module_ent = Schedule::find($module['id']);
        $this->module = $module_ent;
        $this->resource_name = $module_ent->title;
        $this->title = $module_ent->title;
        $this->location = $module_ent->location_str;
        $this->description = $module_ent->description;
        $this->category_id = $module_ent->category_id;
        $this->start_date = $module_ent->latestOccurrence->start_date->format('Y-m-d H:i:s');
        $this->end_date = $module_ent->latestOccurrence->end_date->format('Y-m-d H:i:s');
        $this->reminder_qty = $module_ent->latestOccurrence->reminder_qty;
        $this->reminder_interval_id = $module_ent->latestOccurrence->reminder_interval;

        if ($module_ent->resource && $module_ent->resource->resource_table == 'employees') {
            $employee = $module_ent->resource->employee;
            if ($employee && $employee->schedule == 1) {
                $this->resource_id = $employee->id;
                $this->resource_name = $employee->full_name;
                $this->emit('refreshSearch', ['searchTerm'  => $this->resource_name, 'value' => $this->resource_id,
                                              'description' => $this->resource_name, 'optionsValues' => $this->resource_id]);
            }
        }
    }

    public function setResourceId($object)
    {
        $this->resource_id = $object['value'];
        $this->category_id = 3; // if employee, default to employee appointment
        $this->resetValidation();
    }

    public function setResourceName($object)
    {
        $this->resource_name = $object['description'];
        $this->title = $object['description'];
    }

    public function messages()
    {
        return [
            'title.required_without' => __('bt.validation_title_required'),
            'start_date.required'    => __('bt.validation_start_datetime_required'),
            'end_date.required'      => __('bt.validation_end_datetime_required'),
            'end_date.after'         => __('bt.validation_end_datetime_after'),
        ];
    }

    public function validationAttributes()
    {
        return [
            'resource_id' => trans('bt.employee'),
        ];
    }

    public function doCancel()
    {
        $this->emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
        $this->emit('hideModal');
    }

    public function createEvent()
    {
        $this->validate();

        if (!$this->resource_id && $this->resource_name) {
            $this->title = $this->resource_name;
        } else {
            $this->title = Employee::find($this->resource_id)->short_name;
        }
        $event = ($this->module) ? Schedule::find($this->module->id) : new Schedule();
        $event->title = $this->title;
        $event->location_str = $this->location;
        $event->description = $this->description;
        $event->category_id = $this->category_id;
        $event->user_id = Auth::user()->id;

        $event->save();

        $occurrence = ($this->module) ? ScheduleOccurrence::find($this->module->latestOccurrence->id) : new ScheduleOccurrence();
        $occurrence->schedule_id = $event->id;
        $occurrence->start_date = $this->start_date;
        $occurrence->end_date = $this->end_date;
        $occurrence->reminder_qty = $this->reminder_qty;
        $occurrence->reminder_interval = $this->reminder_interval_id;
        $occurrence->reminder_date = ScheduleOccurrence::reminderDate($this->reminder_qty, $this->reminder_interval_id, $this->start_date);

        $occurrence->save();

        //delete existing resources for the event
        ScheduleResource::where('occurrence_id', '=', $occurrence->id)->forceDelete();

        if ($this->resource_id) {
            $employee = Employee::find($this->resource_id);
            if ($employee && $employee->schedule == 1) { //employee exists and is scheduleable...
                $scheduleItem = ScheduleResource::firstOrNew(['occurrence_id' => $occurrence->id]);
                $scheduleItem->occurrence_id = $occurrence->id;
                $scheduleItem->resource_table = 'employees';
                $scheduleItem->resource_id = $employee->id;
                $scheduleItem->value = $event->title;
                $scheduleItem->qty = 1;
                $scheduleItem->save();
            }
        }

        $msg = trans('bt.record_successfully_created');

        if ($this->fromcalendar) {
            return redirect()->route('scheduler.fullcalendar')->with('alertSuccess', $msg);
        } else {
            return redirect()->route('scheduler.tableevent')->with('alertSuccess', $msg);
        }
    }

    public function render()
    {
        return view('livewire.modals.create-event-modal');
    }
}
