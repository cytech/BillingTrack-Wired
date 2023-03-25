<?php

namespace BT\Http\Livewire;

use BT\Modules\TaxRates\Models\TaxRate;
use Illuminate\Support\Str;
use Livewire\Component;

class ItemsTable extends Component
{
    public $module, $module_type, $module_fullname, $module_id_type, $moduleitem_type, $moduleitem_fullname;
    public $new_item, $module_items = [], $taxRates;
    public $new_item_cfg = [], $readonly = false, $save_item_as = false;
    public $search_mod_fullname, $resource_id, $resource_name;


    // LivewireSelect sets 'name'(component name), 'value'(id), 'description'(name) and 'title'(name/unique_name)
    // from xxxSearch 'id' 'name' 'unique_name/name'
    // listeners then sets $this->resource_id from 'value' and $this->resource_name from 'description'
    protected $listeners = ['resource_idUpdated' => 'setResourceId',
                            'descriptionUpdated' => 'setResourceName',
                            'removeModuleItem'   => 'removeModuleItem', //emit from _js_global swal:deleteConfirm
                            'addItems'           => 'addItems' //emit from AddResourceModal
    ];

    protected function rules()
    {
        return [
            'new_item.' . $this->module_id_type       => 'integer',
            'new_item.id'                             => 'integer',
            'new_item.name'                           => 'required|string',
            'new_item.description'                    => 'required|string',
            'new_item.quantity'                       => 'required|numeric',
            'new_item.price'                          => 'required|numeric',
            'new_item.tax_rate_id'                    => 'integer',
            'new_item.tax_rate_2_id'                  => 'integer',
            'new_item.resource_table'                 => 'string',
            'new_item.resource_id'                    => 'integer',
            'module_items.*.' . $this->module_id_type => 'integer',
            'module_items.*.id'                       => 'integer',
            'module_items.*.name'                     => 'string',
            'module_items.*.description'              => 'string',
            'module_items.*.quantity'                 => 'numeric',
            'module_items.*.price'                    => 'numeric',
            'module_items.*.tax_rate_id'              => 'integer',
            'module_items.*.tax_rate_2_id'            => 'integer',
            'module_items.*.resource_table'           => 'string',
            'module_items.*.resource_id'              => 'integer',
        ];
    }

    public function mount()
    {
        $this->module_fullname = get_class($this->module);
        $this->module_type = class_basename($this->module);
        $this->moduleitem_fullname = get_class($this->module->items()->getRelated());
        $this->moduleitem_type = class_basename($this->module->items()->getRelated());
        $this->new_item_cfg = [Str::snake($this->module_type) . '_id' => $this->module->id,
                               'quantity'                             => 1,
                               'price'                                => 1,
                               'tax_rate_id'                          => config('bt.itemTaxRate'),
                               'tax_rate_2_id'                        => config('bt.itemTax2Rate')];
        $this->new_item = new $this->moduleitem_fullname($this->new_item_cfg);
        $module_id_type = $this->module_id_type = Str::snake($this->module_type) . '_id';
        $this->new_item->$module_id_type = $this->module->id;
        $this->module_items = $this->module->items;
        $this->taxRates = TaxRate::getList();
    }

    public function setResourceId($object)
    {
        $this->resource_id = $object['value'];
        if ($object['value']) {
            $this->search_mod_fullname = 'BT\\Modules\\' . ucfirst($object['name']) . 's\\Models\\' . ucfirst($object['name']);
            $resource = $this->search_mod_fullname::find($object['value']);
            $this->new_item->name = $resource->name;
            $this->new_item->description = $resource->description;
            $this->new_item->price = $resource->price ?? $resource->cost; //product has cost, itemlookup has price, employee has billing_rate
            $this->new_item->tax_rate_id = $resource->tax_rate_id;
            $this->new_item->tax_rate_2_id = $resource->tax_rate_2_id;
            $this->new_item->resource_table = Str::snake($object['name']) . 's';
            $this->new_item->resource_id = $this->resource_id;
        }
    }

    public function setResourceName($object)
    {
        $this->search_mod_fullname = 'BT\\Modules\\' . ucfirst($object['name']) . 's\\Models\\' . ucfirst($object['name']);
        $this->new_item = new $this->moduleitem_fullname($this->new_item_cfg);
        $this->resource_name = $object['description'];
        $this->new_item->name = $object['description'];
    }

    public function addItems($params)
    {
        $search_mod_fullname = 'BT\\Modules\\' . ucfirst($params['resource_type']) . 's\\Models\\' . ucfirst($params['resource_type']);

        foreach ($params['resources'] as $val) {
            $add_item = new $this->moduleitem_fullname($this->new_item_cfg);
            $module_id_type = $this->module_id_type = Str::snake($this->module_type) . '_id';
            $add_item->$module_id_type = $this->module->id;

            $res = $search_mod_fullname::where('id', '=', $val)->firstOrFail();

            $add_item->name = $res->name;
            $add_item->description = $res->description;
            $add_item->price = $res->price;
            $add_item->tax_rate_id = $res->tax_rate_id;
            $add_item->tax_rate_2_id = $res->tax_rate_2_id;
            $add_item->resource_table = Str::snake($params['resource_type']) . 's';
            $add_item->resource_id = $res->id;

            if ($params['resource_type'] == 'Product' && $this->module_type == 'Purchaseorder') {
                $add_item->price = $res->cost;
            }

            if ($params['resource_type'] == 'Employee') {
                $add_item->name = $res->short_name;
                $add_item->description = $res->title;
                $add_item->price = $res->billing_rate;
            }

            $this->module_items->push($add_item);
        }
    }

    public function addItem()
    {
        $this->validate(['new_item.name'        => 'required|string',
                         'new_item.description' => 'required|string',
                         'new_item.quantity'    => 'required|numeric',
                         'new_item.price'       => 'required|numeric',]);
        if ($this->save_item_as && !$this->resource_id) {
            $this->search_mod_fullname::create([
                'name'          => $this->new_item->name,
                'description'   => $this->new_item->description,
                'price'         => $this->new_item->price,
                'tax_rate_id'   => $this->new_item->tax_rate_id ?? 0,
                'tax_rate_2_id' => $this->new_item->tax_rate_2_id ?? 0,
            ]);
        }
        $this->module_items->push($this->new_item);
        $this->clearItem();
    }

    public function clearItem()
    {
        $this->reset('resource_id', 'save_item_as');
        $this->new_item = new $this->moduleitem_fullname($this->new_item_cfg);
        $this->emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
    }

    public function removeItem($index)
    {
        if ($this->module_items[$index]->id) {
            $swaldata = [
                'message'     => __('bt.trash_record_warning'),
                'index'       => $index,
                'id'          => $this->module_items[$index]->id,
                'route'       => route(lcfirst($this->module_type) . 'Item.delete'),
                'totalsRoute' => route(lcfirst($this->module_type) . 's.' . lcfirst($this->module_type) . 'Edit.refreshTotals'),
                'entityID'    => $this->module->id
            ];
            $this->dispatchBrowserEvent('swal:deleteConfirm', $swaldata);
        } else {
            $this->removeModuleItem($index);
        }
    }

    public function removeModuleItem($index)
    {
        $this->module_items->forget($index);
    }

    public function render()
    {
        return view('livewire.items-table');
    }
}
