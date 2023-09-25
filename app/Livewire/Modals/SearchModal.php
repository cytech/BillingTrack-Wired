<?php

namespace BT\Livewire\Modals;

use Livewire\Attributes\On;
use Livewire\Component;

class SearchModal extends Component
{
    public $search_type;

    public $resource_id;

    public $resource_name;

    public $module;

    protected $rules = [
        'resource_id' => 'required',
        'module' => 'required',
    ];

    public function messages()
    {
        return [
            'resource_id.required' => __('bt.resource_not_found'),
        ];
    }

    public function mount($modulefullname, $module_id, $search_type)
    {
        $this->module = $modulefullname::find($module_id);
        $this->search_type = $search_type;
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

    public function doCancel()
    {
        $this->dispatch('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
        $this->dispatch('hideModal');
    }

    public function changeResource()
    {
        $this->validate();
        if (class_basename($this->module) == 'Purchaseorder') {
            $this->module->update(['vendor_id' => $this->resource_id]);
        } else {
            $this->module->update(['client_id' => $this->resource_id]);
        }
        $this->dispatch('hideModal');
        $this->dispatch('resource-changed', id: $this->module->id);
    }

    public function render()
    {
        return view('livewire.modals.search-modal');
    }
}
