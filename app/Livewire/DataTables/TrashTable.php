<?php

namespace BT\Livewire\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TrashTable extends DataTableComponent
{
    public $module_type;

    public $module_fullname;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchDisabled();
        $this->setTableAttributes([
            'default' => true,
            'class' => 'datatable',
        ]);

        $this->setTheadAttributes([
            'default' => true,
            'class' => 'bg-body lwtable',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id') || $column->isLabel()) {
                return [
                    'default' => true,
                    'width' => '8%',
                ];
            }

            return [];
        });
        if ($this->module_type == 'Orphaned') {
            $this->setBulkActionsDisabled();
        }
    }

    public function mount()
    {
        if ($this->module_type == 'TimeTrackingProject') {
            $this->module_fullname = 'BT\\Modules\\TimeTracking\\Models\\TimeTrackingProject';
        } elseif ($this->module_type == 'Schedule') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Schedule';
        } elseif ($this->module_type == 'Orphaned') {
            $this->module_fullname = 'BT\\Modules\\Documents\\Models\\Document';
        } elseif (in_array($this->module_type, ['Invoice', 'Quote', 'Workorder', 'Purchaseorder', 'Recurringinvoice'])) {
            $this->module_fullname = 'BT\\Modules\\Documents\\Models\\'.$this->module_type;
        } else {
            $this->module_fullname = 'BT\\Modules\\'.$this->module_type.'s\\Models\\'.$this->module_type;
        }
    }

    public function columns(): array
    {
        if (in_array($this->module_type, ['Invoice', 'Quote', 'Workorder', 'Purchaseorder', 'Recurringinvoice'])) {
            $status_model = 'BT\\Support\\Statuses\\DocumentStatuses';
        } else {
            $status_model = 'BT\\Support\\Statuses\\'.$this->module_type.'Statuses';
        }
        $statuses = class_exists($status_model) ? $status_model::listsAllFlat() + ['overdue' => trans('bt.overdue')] : null;

        return TrashColumnDefs::columndefs($statuses, $this->module_type);
    }

    public function bulkActions(): array
    {
        return [
            'restore' => __('bt.restore'),
            'delete' => __('bt.delete'),
        ];
    }

    public function restore(): void
    {
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
            $swaldata = [
                'title' => __('bt.trash_restoreselected_warning'),
                'ids' => $ids,
                'module_type' => $this->module_type,
                'route' => route('utilities.bulk.restoretrash'),
            ];
            $this->dispatch('swal:bulkConfirm', ...$swaldata);
        }
        $this->clearSelected();
    }

    public function delete(): void
    {
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
            $swaldata = [
                'title' => __('bt.bulk_delete_record_warning'),
                'ids' => $ids,
                'module_type' => $this->module_type,
                'route' => route('utilities.bulk.deletetrash'),
            ];
            $this->dispatch('swal:bulkConfirm', ...$swaldata);
        }
        $this->clearSelected();
    }

    #[On('reset_bulk_select')]
    public function resetBulkSelect()
    {
        $this->clearSelected();
    }

    public function builder(): Builder
    {
        if ($this->module_type == 'Purchaseorder') {
            return $this->module_fullname::has('vendor')->with('vendor')->onlyTrashed()->select('documents.*');
        } elseif ($this->module_type == 'Client') {
            return $this->module_fullname::getSelect()->onlyTrashed();
        } elseif ($this->module_type == 'Vendor') {
            return $this->module_fullname::getSelect()->onlyTrashed();
        } elseif ($this->module_type == 'Payment') {
            return $this->module_fullname::has('client')->with('client')->select('payments.*')->onlyTrashed();
        } elseif ($this->module_type == 'Expense') {
            return $this->module_fullname::defaultQuery()->onlyTrashed();
        } elseif ($this->module_type == 'TimeTrackingProject') {
            return $this->module_fullname::has('client')->with('client')->getSelect()->onlyTrashed();
        } elseif ($this->module_type == 'Schedule') {
            return $this->module_fullname::with(['latestOccurrence' => function ($q) {
                $q->onlyTrashed();
            }, 'category'])->select('schedule.*')->onlyTrashed();
        } elseif ($this->module_type == 'Orphaned') {
            return $this->module_fullname::whereDoesntHave('trashedvendor')->where('document_type', 'BT\Modules\Documents\Models\Purchaseorder')
                ->orWhereDoesntHave('trashedclient')->where('document_type', '!=', 'BT\Modules\Documents\Models\Purchaseorder')
                ->select('documents.*')->onlyTrashed();
        } else {
            return $this->module_fullname::has('client')->with('client')->onlyTrashed()->select('documents.*');
        }
    }
}
