<?php

namespace BT\Http\Livewire;

use BT\Modules\Products\Models\Product;
use Illuminate\Support\Collection;

class ProductSearch extends LivewireSelect
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
        return Product::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")->where('active', 1)->orderby('name','ASC');
            })
            ->get()
            ->map(function (Product $product) {
                return [
                    'value'       => $product->id,
                    'description' => $product->name,
                    'title'       => $product->serialnum,
                ];
            });
    }

    public function selectedOption($value)
    {
        $product = Product::find($value);
        return [
            'value'       => optional($product)->id,
            'description' => optional($product)->name,
            'title'       => optional($product)->serialnum
        ];
    }
}
