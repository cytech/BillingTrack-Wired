<?php

namespace BT\Http\Livewire;

use Illuminate\Support\Collection;
use BT\Modules\Vendors\Models\Vendor;

class VendorSearch extends LivewireSelect
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
        return Vendor::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")->where('active', 1);
            })
            ->get()
            ->map(function (Vendor $vendor) {
                return [
                    'value'       => $vendor->id,
                    'description' => $vendor->name,
                    'title'       => $vendor->name,
                ];
            });
    }

    public function selectedOption($value)
    {
        $vendor = Vendor::find($value);
        return [
            'value'       => optional($vendor)->id,
            'description' => optional($vendor)->name,
            'title'       => optional($vendor)->name
        ];
    }
}
