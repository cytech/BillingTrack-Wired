<?php

namespace BT\Http\Livewire\Modals;

use Livewire\Component;

class AddResourceModal extends Component
{
    public $resources, $resource_type, $pref_vendor = null, $selected_resources = [];
    public $module;

    protected function rules()
    {
        return [
            'resources.*.id' => 'integer',
        ];
    }

    public function mount($modulefullname, $module_id, $resource_type){
        $this->module = $modulefullname::find($module_id);
        $module_type = class_basename($this->module);
        $this->resource_type = $resource_type;
        if ($resource_type == 'Product') {
            if ($module_type == 'Purchaseorder') {
                $this->pref_vendor = 1;
                $this->resources = ('BT\\Modules\\' . $resource_type . 's\\Models\\' . $resource_type)::status('active')->where('vendor_id', $this->module->vendor_id)->orderby('name','ASC')->get();
            }else{
                $this->resources = ('BT\\Modules\\' . $resource_type . 's\\Models\\' . $resource_type)::status('active')->orderby('name','ASC')->get();
            }
        }elseif ($resource_type == 'Employee') {
            $this->resources = ('BT\\Modules\\' . $resource_type . 's\\Models\\' . $resource_type)::status('active')->orderBy('full_name')->get();
        }else{ //ItemLookup
            $this->resources = ('BT\\Modules\\' . $resource_type . 's\\Models\\' . $resource_type)::orderby('resource_table','ASC')->orderby('name','ASC')->get();
        }
    }

    public function updatedPrefVendor(){
        $this->pref_vendor ? !$this->pref_vendor : 1;
        if ($this->pref_vendor){
            $this->resources = ('BT\\Modules\\' . $this->resource_type . 's\\Models\\' . $this->resource_type)::status('active')->where('vendor_id', $this->module->vendor_id)->orderby('name','ASC')->get();
        }else {
            $this->resources = ('BT\\Modules\\' . $this->resource_type . 's\\Models\\' . $this->resource_type)::status('active')->orderby('name','ASC')->get();
        }
    }

    public function doCancel()
    {
        $this->emit('hideModal');
    }

    public function addItems()
    {
        $this->emit('addItems', ['resources' => $this->selected_resources, 'resource_type' => $this->resource_type]);
        // Close Modal After Logic
        $this->emit('hideModal');
    }

    public function render()
    {
        return view('livewire.modals.add-resource-modal');
    }
}
