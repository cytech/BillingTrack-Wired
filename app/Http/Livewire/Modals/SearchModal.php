<?php

namespace BT\Http\Livewire\Modals;

use Livewire\Component;

class SearchModal extends Component
{
    public $search_type, $resource_id, $resource_name, $module;

    protected $listeners = ['resource_idUpdated'   => 'setResourceId',
                            'descriptionUpdated' => 'setResourceName',];

    protected $rules = [
        'resource_id'         => 'required',
        'module'        => 'required',
    ];

    public function messages()
    {
        return [
            'resource_id.required' => __('bt.resource_not_found')
        ];
    }

    public function mount($modulefullname, $module_id = null, $search_type){
        $this->module = $modulefullname::find($module_id);
        $this->search_type = $search_type;
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

    public function doCancel()
    {
        $this->emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
        $this->emit('hideModal');
    }

    public function changeResource(){
        $this->validate();
        if (class_basename($this->module) == 'Purchaseorder'){
            $this->module->update(['vendor_id' => $this->resource_id]);
        }else {
            $this->module->update(['client_id' => $this->resource_id]);
        }
        $this->emit('hideModal');
        $this->emit('resource-changed', $this->module->id);
    }

    public function render()
    {
        return view('livewire.modals.search-modal');
    }
}
