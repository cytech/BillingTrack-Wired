<?php

namespace BT\Http\Livewire;

use Illuminate\Support\Collection;
use BT\Modules\Employees\Models\Employee;

class EmployeeSearch extends LivewireSelect
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
        return Employee::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('full_name', 'like', "%$searchTerm%")->where('active', 1)->orderBy('full_name');
            })
            ->get()
            ->map(function (Employee $employee) {
                return [
                    'value'       => $employee->id,
                    'description' => $employee->short_name,
                    'title'       => $employee->full_name,
                ];
            });
    }

    public function selectedOption($value)
    {
        $employee = Employee::find($value);
        return [
            'value'       => optional($employee)->id,
            'description' => optional($employee)->short_name,
            'title'       => optional($employee)->full_name
        ];
    }
}
