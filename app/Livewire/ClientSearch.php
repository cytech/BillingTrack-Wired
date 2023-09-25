<?php

namespace BT\Livewire;

use BT\Modules\Clients\Models\Client;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class ClientSearch extends LivewireSelect
{
    #[On('refreshSearch')]
    public function refreshSearch($props)
    {
        $this->searchTerm = $props['searchTerm'];
        $this->value = $props['value'];
        $this->description = $props['description'];
        $this->optionsValues = $props['optionsValues'];
    }

    public function options($searchTerm = null, $extras = null): Collection
    {
        if ($extras == 'clientuserfilter') {
            return Client::query()
                ->when($searchTerm, function ($query, $searchTerm) {
                    $query->where('name', 'like', "%$searchTerm%")
                        ->whereDoesntHave('user')
                        ->where('email', '<>', '')
                        ->whereNotNull('email')
                        ->where('active', 1)
                        ->orderBy('name');
                })
                ->get()
                ->map(function (Client $client) {
                    return [
                        'value' => $client->id,
                        'description' => $client->name,
                        'title' => $client->email,
                    ];
                });

        } else {
            return Client::query()
                ->when($searchTerm, function ($query, $searchTerm) {
                    $query->where('name', 'like', "%$searchTerm%")->where('active', 1)->orderBy('name');
                })
                ->get()
                ->map(function (Client $client) {
                    return [
                        'value' => $client->id,
                        'description' => $client->name,
                        'title' => $client->unique_name,
                    ];
                });
        }
    }

    public function selectedOption($value)
    {
        $client = Client::find($value);

        return [
            'value' => optional($client)->id,
            'description' => optional($client)->name,
            'title' => optional($client)->unique_name,
        ];
    }
}