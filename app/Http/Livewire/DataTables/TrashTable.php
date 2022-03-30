<?php

namespace BT\Http\Livewire\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TrashTable extends DataTableComponent
{
    public bool $showSearch = false;
    public $module_type, $module_fullname;

    protected $listeners = ['reset_bulk_select' => 'resetBulkSelect'];  //emit from _js_global swal:bulkConfirm

    public function mount()
    {
        if ($this->module_type == 'TimeTrackingProject') {
            $this->module_fullname = 'BT\\Modules\\TimeTracking\\Models\\TimeTrackingProject';
        } elseif ($this->module_type == 'Schedule') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Schedule';
        } else {
            $this->module_fullname = 'BT\\Modules\\' . $this->module_type . 's\\Models\\' . $this->module_type;
        }
    }

    public function columns(): array
    {
        $status_model = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';
        $statuses = class_exists($status_model) ? $status_model::listsAllFlat() + ['overdue' => trans('bt.overdue')] : null;

        return TrashColumnDefs::columndefs($statuses, $this->module_type);
    }

    public function bulkActions(): array
    {
        return [
            'restore' => __('bt.restore'),
            'delete'  => __('bt.delete'),
        ];
    }

    public function setTableClass(): ?string
    {
        return 'table dataTable';
    }

    public function restore(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            $ids = $this->selectedKeys();
            $swaldata = [
                'title'     => __('bt.trash_restoreselected_warning'),
                'ids'         => $ids,
                'module_type' => $this->module_type,
                'route'       => route('utilities.bulk.restoretrash'),
            ];
            $this->dispatchBrowserEvent('swal:bulkConfirm', $swaldata);
        }
        $this->resetBulk();
    }

    public function delete(): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            $ids = $this->selectedKeys();
            $swaldata = [
                'title' => __('bt.bulk_delete_record_warning'),
                'ids'     => $ids,
                'module_type' => $this->module_type,
                'route'   => route('utilities.bulk.deletetrash'),
            ];
            $this->dispatchBrowserEvent('swal:bulkConfirm', $swaldata);
        }
        $this->resetBulk();
    }

    public function resetBulkSelect()
    {
        $this->resetBulk();
    }

    public function query(): Builder
    {
        if ($this->module_type == 'Purchaseorder') {
            return $this->module_fullname::has('vendor')->with('vendor')->onlyTrashed();
        } elseif ($this->module_type == 'Client') {
            return $this->module_fullname::onlyTrashed();
        } elseif ($this->module_type == 'Expense') {
            return $this->module_fullname::defaultQuery()->onlyTrashed();
        } elseif ($this->module_type == 'TimeTrackingProject') {
            return $this->module_fullname::has('client')->with('client')->getSelect()->onlyTrashed();
        } elseif ($this->module_type == 'Schedule') {
            return $this->module_fullname::with(['latestOccurrence' => function ($q) {
                $q->onlyTrashed();
            }, 'category'])->select('schedule.*')->onlyTrashed();
        } else {
            return $this->module_fullname::has('client')->with('client')->onlyTrashed();
        }
    }
}
