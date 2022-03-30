<?php

namespace BT\Http\Livewire;

use BT\Modules\ItemLookups\Models\ItemLookup;
use Illuminate\Support\Collection;

class ItemLookupSearch extends LivewireSelect
{
    protected $listeners = ['refreshSearch'];

    public function refreshSearch($props){
        $this->searchTerm = $props['searchTerm'];
        $this->value = $props['value'];
        $this->description = $props['description'];
        $this->optionsValues = $props['optionsValues'];
    }

    public function options($searchTerm = null): Collection
    {
        return ItemLookup::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")->orderBy('resource_table','ASC')->orderBy('name','ASC');
            })
            ->get()
            ->map(function (ItemLookup $itemlookup) {
                return [
                    'value'       => $itemlookup->id,
                    'description' => $itemlookup->name,
                    'title'       => $itemlookup->description,
                ];
            });
    }

    public function selectedOption($value)
    {
        $itemlookup = ItemLookup::find($value);
        return [
            'value'       => optional($itemlookup)->id,
            'description' => optional($itemlookup)->name,
            'title'       => optional($itemlookup)->description
        ];
    }
}
