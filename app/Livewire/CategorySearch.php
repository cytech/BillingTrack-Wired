<?php

namespace BT\Livewire;

use BT\Modules\Categories\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class CategorySearch extends LivewireSelect
{
    #[On('refreshSearch')]
    public function refreshSearch($props): void
    {
        $this->searchTerm = $props['searchTerm'];
        $this->value = $props['value'];
        $this->description = $props['description'];
        $this->optionsValues = $props['optionsValues'];
    }

    public function options($searchTerm = null): Collection
    {
        return Category::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")->orderBy('name');
            })
            ->get()
            ->map(function (Category $category) {
                return [
                    'value' => $category->id,
                    'description' => $category->name,
                    'title' => $category->name,
                ];
            });
    }

    public function selectedOption($value): array
    {
        $category = Category::find($value);

        return [
            'value' => optional($category)->id,
            'description' => optional($category)->name,
            'title' => optional($category)->name,
        ];
    }
}
