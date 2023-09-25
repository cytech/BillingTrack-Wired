<?php

namespace BT\Livewire\Modals;

use BT\Events\DocumentModified;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Products\Models\Product;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\Frequency;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateModuleModal extends Component
{
    public $moduletype;

    public $moduleop;

    public $module_id;

    public $modulefullname;

    public $show;

    public $readonly;

    public $lineitem;

    public $companyProfiles;

    public $groups;

    public $module_date;

    public $resource_id;

    public $resource_name;

    public $group_id;

    public $company_profile_id;

    public $user_id;

    public $frequencies;

    public $next_date;

    public $stop_date;

    public $recurring_frequency;

    public $recurring_period;

    public $orig_resource_id;

    public $orig_resource_name;

    public $orig_group_id;

    public $orig_company_profile_id;

    // LivewireSelect sets 'name'(component name), 'value'(id), 'description'(name) and 'title'(name/unique_name)
    // from xxxSearch 'id' 'name' 'unique_name/name'
    // listeners then sets $this->resource_id from 'value' and $this->resource_name from 'description'

    protected $rules = [
        'company_profile_id' => 'required|integer|exists:company_profiles,id',
        'resource_id' => 'required_without:resource_name',
        'resource_name' => 'required_without:resource_id',
        'module_date' => 'required',
        'user_id' => 'required',
        'next_date' => 'required',
    ];

    /**
     * parameters sent by showModal array
     *
     * @param $modulefullname
     * fully namespaced model
     * @param $module_type
     * module baseclass
     * @param $moduleop
     * copy or create
     * @param  null  $resource_id
     * resource id (client, vendor, etc)
     * @param  null  $module_id
     * module id (quote, workorder, etc)
     * @param  null  $readonly
     * @param  null  $lineitem
     * item resource id to add item to module create - currently only purchaseorder item from Products
     */
    public function mount($modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null, $lineitem = null)
    {
        $this->modulefullname = $modulefullname;
        $this->moduletype = $module_type;
        $this->moduleop = $moduleop;

        if ($resource_id) {
            if ($this->moduletype == 'Purchaseorder') {
                $resource = Vendor::find($resource_id);
            } else {
                $resource = Client::find($resource_id);
            }
            $this->resource_id = $resource->id;
            $this->resource_name = $resource->name;
            $this->dispatch('refreshSearch', ['searchTerm' => $this->resource_name, 'value' => $this->resource_id,
                'description' => $this->resource_name, 'optionsValues' => $this->resource_id]);
        }
        if ($this->moduletype == 'Purchaseorder' && $lineitem) {
            $this->lineitem = Product::find($lineitem);
        }

        if ($module_id) {
            $module_model = $modulefullname::find($module_id);
            $this->module_id = $module_model->id;
            $this->company_profile_id = $module_model->company_profile_id;
            $this->group_id = $module_model->group_id;
        } else {
            $this->company_profile_id = config('bt.defaultCompanyProfile');
            $this->group_id = config('bt.'.lcfirst($this->moduletype).'Group');
        }

        if ($readonly) {
            $this->readonly = true;
        }

        $this->companyProfiles = CompanyProfile::getList();
        $this->groups = Group::getList();
        $this->frequencies = Frequency::lists();
        $this->user_id = auth()->user()->id;
        $this->module_date = date('Y-m-d');
        $this->next_date = date('Y-m-d');
        $this->stop_date = null;
        $this->recurring_frequency = 1;
        $this->recurring_period = 3;
        $this->orig_resource_id = $this->resource_id;
        $this->orig_resource_name = $this->resource_name;
        $this->orig_company_profile_id = $this->company_profile_id;
        $this->orig_group_id = $this->group_id;
        $this->show = false;
    }

    #[On('resource_idUpdated')]
    public function setResourceId($object)
    {
        $this->resource_id = $object['value'];
        $this->resetValidation();
    }

    #[On('descriptionUpdated')]
    public function setResourceName($object)
    {
        $this->resource_name = $object['description'];
    }

    public function validationAttributes()
    {
        return [
            'company_profile_id' => trans('bt.company_profile'),
            'resource_name' => trans('bt.client'),
            'resource_id' => trans('bt.client'),
            'user_id' => trans('bt.user'),
            'module_date' => trans('bt.date'),
            'group_id' => trans('bt.group'),
            'next_date' => trans('bt.start_date'),
            'stop_date' => trans('bt.stop_date'),
        ];
    }

    public function messages()
    {
        return [
            'resource_name.required_without' => __('bt.validation_resource_name_required'),
        ];
    }

    public function updatedModuleDate()
    {
        $this->validateOnly('module_date');
    }

    public function updatedNextDate()
    {
        $this->validateOnly('next_date');
    }

    public function updatedStopDate()
    {
        $this->validateOnly('stop_date');
    }

    public function doCancel()
    {
        if ($this->moduleop == 'create' && ! $this->readonly) {
            $this->dispatch('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
        } elseif ($this->moduleop == 'copy' || $this->readonly) {
            $this->resource_id = $this->orig_resource_id;
            $this->resource_name = $this->orig_resource_name;
            $this->company_profile_id = $this->orig_company_profile_id;
            $this->group_id = $this->orig_group_id;
            $this->dispatch('refreshSearch', ['searchTerm' => $this->resource_name, 'value' => $this->resource_id,
                'description' => $this->resource_name, 'optionsValues' => $this->resource_id]);
        }

        $this->dispatch('hideModal');
    }

    public function createModule()
    {
        if ($this->moduleop == 'create') {
            if ($this->moduletype == 'Purchaseorder') {
                $searchmodel = Vendor::class;
                $swaldatatext = __('bt.creating_new_vendor');
            } else {
                $searchmodel = Client::class;
                $swaldatatext = __('bt.creating_new_client');
            }
            if (! $this->resource_id && $this->resource_name) {
                $this->resource_id = $searchmodel::firstOrCreateByName(null, $this->resource_name)->id;
                $swaldata['text'] = $swaldatatext;
            }
            // Quote, Workorder, Invoice, Purchaseorder, Recurringinvoice
            $createfields = [
                'document_date' => $this->module_date ?? date('Y-m-d'),
                'user_id' => $this->user_id,
                'client_id' => $this->resource_id,
                'group_id' => $this->group_id,
                'company_profile_id' => $this->company_profile_id,
                'next_date' => $this->next_date ?? null,
                'stop_date' => $this->stop_date ?? '0000-00-00',
                'recurring_frequency' => $this->recurring_frequency ?? null,
                'recurring_period' => $this->recurring_period ?? null,
            ];

            $this->validate();

            $swaldata['message'] = __('bt.saving');
            $this->dispatch('swal:saving', ...$swaldata);

            $module = $this->modulefullname::create($createfields);
            //currently only Purchaseorder Item from Products
            if ($this->moduletype == 'Purchaseorder' && $this->lineitem) {
                DocumentItem::create([
                    'document_id' => $module->id,
                    'name' => $this->lineitem->name,
                    'description' => $this->lineitem->description,
                    'quantity' => 1,
                    'price' => $this->lineitem->cost,
                    'tax_rate_id' => $this->lineitem->tax_rate_id ?? 0,
                    'tax_rate_2_id' => $this->lineitem->tax_rate_2_id ?? 0,
                    'resource_table' => 'products',
                    'resource_id' => $this->lineitem->id,
                    'display_order' => 1,
                ]);

            }
            event(new DocumentModified($module));
            // Close Modal After Logic
            $this->dispatch('hideModal');

            return redirect()->route('documents.edit', $module->id)
                ->with('alertSuccess', trans('bt.record_successfully_created'));

        } elseif ($this->moduleop == 'copy') {
            $this->copyModule();
        }
    }

    public function copyModule()
    {
        if ($this->moduletype == 'Purchaseorder') {
            $searchmodel = Vendor::class;
            $swaldatatext = __('bt.creating_new_vendor');
        } else {
            $searchmodel = Client::class;
            $swaldatatext = __('bt.creating_new_client');
        }
        if (! $this->resource_id && $this->resource_name) {
            $this->resource_id = $searchmodel::firstOrCreateByName(null, $this->resource_name)->id;
            $swaldata['text'] = $swaldatatext;
        }
        $fromModule = $this->modulefullname::find($this->module_id);
        $createfields = [
            'document_date' => $this->module_date ?? date('Y-m-d'),
            'user_id' => $this->user_id,
            'client_id' => $this->resource_id,
            'group_id' => $this->moduletype == 'Recurringinvoice' ? config('bt.recurringinvoiceGroup') : $this->group_id,
            'company_profile_id' => $this->company_profile_id,
            'currency_code' => $fromModule->currency_code,
            'exchange_rate' => $fromModule->exchange_rate,
            'terms' => $fromModule->terms,
            'footer' => $fromModule->footer,
            'template' => $fromModule->template,
            'summary' => $fromModule->summary,
            'discount' => $fromModule->discount,
            'next_date' => $this->next_date ?? null,
            'stop_date' => $this->stop_date ?? '0000-00-00',
            'recurring_frequency' => $this->recurring_frequency ?? null,
            'recurring_period' => $this->recurring_period ?? null,
        ];

        $this->validate();
        $swaldata['message'] = __('bt.saving');

        $this->dispatch('swal:saving', ...$swaldata);

        $toModule = $this->modulefullname::create($createfields);

        if ($this->moduletype == 'Workorder') {
            $toModule->job_date = $fromModule->job_date;
            $toModule->start_time = $fromModule->start_time;
            $toModule->end_time = $fromModule->end_time;
            $toModule->will_call = $fromModule->will_call;
            $toModule->save();
        }

        foreach ($fromModule->items as $item) {
            DocumentItem::create(
                [
                    'document_id' => $toModule->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'tax_rate_id' => $item->tax_rate_id,
                    'tax_rate_2_id' => $item->tax_rate_2_id,
                    'resource_table' => $item->resource_table,
                    'resource_id' => $item->resource_id,
                    'display_order' => $item->display_order,
                ]);
        }

        // Copy the custom fields
        $custom = collect($fromModule->custom)->except(lcfirst(snake_case($this->moduletype)).'_id')->toArray();
        $toModule->custom->update($custom);

        event(new DocumentModified($toModule));
        // Close Modal After Logic
        $this->dispatch('hideModal');

        return redirect()->route('documents.edit', $toModule->id)
            ->with('alertSuccess', trans('bt.record_successfully_created'));
    }

    public function render()
    {
        return view('livewire.modals.create-module-modal');
    }
}
