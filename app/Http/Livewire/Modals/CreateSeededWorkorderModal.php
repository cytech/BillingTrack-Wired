<?php

namespace BT\Http\Livewire\Modals;

use BT\Events\WorkorderModified;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Products\Models\Product;
use BT\Modules\Scheduler\Controllers\SchedulerController;
use BT\Modules\Workorders\Models\Workorder;
use BT\Modules\Workorders\Models\WorkorderItem;
use BT\Support\Statuses\WorkorderStatuses;
use Carbon\Carbon;
use Livewire\Component;

class CreateSeededWorkorderModal extends Component
{
    public $module_id;
    public $show, $readonly = false, $returnurl;
    public $companyProfiles, $groups, $module_date, $resource_id, $resource_name, $group_id, $company_profile_id, $user_id;
    public $job_date, $summary, $start_time, $end_time, $will_call = 0;
    public $available_employees, $available_resources, $selected_employees = [], $selected_resources = [], $selected_qty = [];

    protected $listeners = ['resource_idUpdated'   => 'setResourceId',
                            'descriptionUpdated' => 'setResourceName',];

    protected $rules = [
        'company_profile_id' => 'required|integer|exists:company_profiles,id',
        'resource_id'          => 'required_without:resource_name',
        'resource_name'        => 'required_without:resource_id',
        'module_date'        => 'required',
        'user_id'            => 'required',
        'start_time'         => 'required',
        'end_time'           => 'required|after:start_time'
    ];

    public function mount($date, $returnurl = null)
    {
        $this->module_date = Date('Y-m-d');
        $this->job_date = Carbon::parse($date)->format('Y-m-d');
        $this->returnurl = $returnurl;
        $this->start_time = '08:00';
        $this->end_time = '09:00';
        $this->company_profile_id = config('bt.defaultCompanyProfile');
        $this->group_id = config('bt.workorderGroup');
        $this->companyProfiles = CompanyProfile::getList();
        $this->groups = Group::getList();
        $this->user_id = auth()->user()->id;
        $this->module_date = date('Y-m-d');
        $this->show = false;
        list($this->available_employees, $this->available_resources) = (new SchedulerController)->getResourceStatus(Carbon::parse($date));
    }

    public function setResourceId($object)
    {
        $this->resource_id = $object['value'];
        $this->resetValidation();
    }

    public function setResourceName($object)
    {
        $this->resource_name = $object['description'];
    }

    public function validationAttributes()
    {
        return [
            'resource_name' => trans('bt.client'),
            'resource_id'   => trans('bt.client'),
            'start_time'  => trans('bt.start_time'),
            'end_time'    => trans('bt.end_time')
        ];
    }

    public function messages()
    {
        return [
            'resource_name.required_without' => __('bt.validation_resource_name_required'),
            'end_time.after'               => __('bt.validation_end_time_after'),
        ];
    }

    public function doCancel()
    {
        $this->emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);

        $this->emit('hideModal');
    }

    public function createModule()
    {
        if (!$this->resource_id && $this->resource_name) {
            $this->resource_id = Client::firstOrCreateByName(null, $this->resource_name)->id;
            $swaldata['text'] = __('bt.creating_new_client');
        }

        $res = [];
        foreach ($this->selected_resources as $resource) {
            if ($resource) {
                foreach ($this->selected_qty as $key => $value) {
                    if ($key == $resource) {
                        $res[$key] = $value;
                    }
                }
            }
        }

        $createfields = [
            'workorder_date'     => $this->module_date,
            'user_id'            => $this->user_id,
            'client_id'          => $this->resource_id,
            'group_id'           => $this->group_id,
            'workorder_status_id'=> WorkorderStatuses::getStatusId('approved'),
            'company_profile_id' => $this->company_profile_id,
            'summary'            => $this->summary,
            'job_date'           => $this->job_date,
            'start_time'         => $this->start_time,
            'end_time'           => $this->end_time,
            'will_call'          => $this->will_call
        ];

        $this->validate();

        $swaldata['message'] = __('bt.saving');
        $this->dispatchBrowserEvent('swal:saving', $swaldata);

        $module = Workorder::create($createfields);
        // Now let's add some employee items to that new workorder.
        if ($this->selected_employees) {
            foreach ($this->selected_employees as $val) {
                $lookupItem = Employee::where('id', '=', $val)->firstOrFail();
                $item['workorder_id'] = $module->id;
                $item['resource_table'] = 'employees';
                $item['resource_id'] = $lookupItem->id;
                $item['name'] = $lookupItem->short_name;
                $item['description'] = $lookupItem->title . "-" . $lookupItem->number;
                $item['quantity'] = 0;
                $item['price'] = $lookupItem->billing_rate;

                WorkorderItem::create($item);
            }
        }
        // Now let's add some resource items to that new workorder.
        if ($res) {
            foreach ($res as $k => $v) {
                $lookupItem = Product::where('id', '=', $k)->firstOrFail();
                $item['workorder_id'] = $module->id;
                $item['resource_table'] = 'products';
                $item['resource_id'] = $lookupItem->id;
                $item['name'] = $lookupItem->name;
                $item['description'] = $lookupItem->description;
                $item['quantity'] = $v;
                $item['price'] = $lookupItem->price;

                WorkorderItem::create($item);
            }
        }

        event(new WorkorderModified(Workorder::find($module->id)));
        // Close Modal After Logic
        $this->emit('hideModal');
        $this->dispatchBrowserEvent('swal:saved', ['message' => trans('bt.record_successfully_created')]);

        if(!$module->client->address) {
            return redirect()->route('workorders.edit', $module->id);
        }else{
            return redirect()->route('scheduler.'.$this->returnurl);
        }
    }

    public function render()
    {
        return view('livewire.modals.create-seeded-workorder-modal');
    }
}
