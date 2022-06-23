<?php

namespace BT\Http\Livewire\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ModuleTable extends DataTableComponent
{
    //public bool $showSearch = false;
    public string $defaultSortColumn = '';
    public string $defaultSortDirection = 'asc';
    public $module_type, $module_fullname, $keyedStatuses, $reqstatus;
    public bool $responsive = false; // true causing z-index issues with action dropdown

    protected $listeners = ['reset_bulk_select' => 'resetBulkSelect'];  //emit from _js_global swal:bulkConfirm

    public function mount()
    {
        $this->reqstatus = request('status');

        if ($this->module_type == 'TimeTrackingProject') {
            $this->module_fullname = 'BT\\Modules\\TimeTracking\\Models\\TimeTrackingProject';
        } elseif ($this->module_type == 'Schedule' || $this->module_type == 'RecurringEvent') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Schedule';
        } elseif ($this->module_type == 'ScheduleCategory') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Category';
            $this->showSearch = false;
        } elseif ($this->module_type == 'Employee') {
            $this->module_fullname = 'BT\\Modules\\Employees\\Models\\Employee';
        } elseif ($this->module_type == 'Vendor') {
            $this->module_fullname = 'BT\\Modules\\Vendors\\Models\\Vendor';
        } elseif ($this->module_type == 'Product') {
            $this->module_fullname = 'BT\\Modules\\Products\\Models\\Product';
        } elseif ($this->module_type == 'Category') {
            $this->module_fullname = 'BT\\Modules\\Categories\\Models\\Category';
            $this->showSearch = false;
        } elseif ($this->module_type == 'ItemLookup') {
            $this->module_fullname = 'BT\\Modules\\ItemLookups\\Models\\ItemLookup';
        } elseif ($this->module_type == 'MailQueue') {
            $this->module_fullname = 'BT\\Modules\\MailQueue\\Models\\MailQueue';
            $this->showSearch = false;
        } elseif ($this->module_type == 'User') {
            $this->module_fullname = 'BT\\Modules\\Users\\Models\\User';
            $this->showSearch = false;
        } elseif ($this->module_type == 'Client') {
            $this->defaultSortColumn = 'name';
            $this->defaultSortDirection = 'asc';
            $this->module_fullname = 'BT\\Modules\\Clients\\Models\\Client';
        } elseif ($this->module_type == 'RecurringInvoice') {
            $this->defaultSortColumn = 'client_id';
            $this->defaultSortDirection = 'asc';
            $this->module_fullname = 'BT\\Modules\\RecurringInvoices\\Models\\RecurringInvoice';
        } elseif ($this->module_type == 'Payment') {
            $this->defaultSortColumn = 'paid_at';
            $this->defaultSortDirection = 'asc';
            $this->module_fullname = 'BT\\Modules\\Payments\\Models\\Payment';
        } else {
            $this->defaultSortColumn = lcfirst($this->module_type) . '_date';
            $this->defaultSortDirection = 'desc';
            $this->module_fullname = 'BT\\Modules\\' . $this->module_type . 's\\Models\\' . $this->module_type;
        }
    }

    public function resetBulkSelect()
    {
        $this->resetBulk();
    }

    public function columns(): array
    {
        $status_model = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';
        $statuses = class_exists($status_model) ? $status_model::listsAllFlat() + ['overdue' => trans('bt.overdue')] : null;

        return ModuleColumnDefs::columndefs($statuses, $this->module_type);
    }

    public function bulkActions(): array
    {
        $no_bulk_actions = ['ScheduleCategory', 'Employee', 'Vendor', 'Product', 'Category', 'ItemLookup', 'MailQueue', 'User'];
        $trash_only_action = ['Client', 'RecurringInvoice', 'Payment', 'Expense', 'Schedule', 'RecurringEvent'];
        //do not allow bulk actions on invoices if update inventory products is enabled
        if ($this->module_type == 'Invoice' && config('bt.updateInvProductsDefault')) {
            return [];
        }
        if (in_array($this->module_type, $no_bulk_actions)) { //no bulk actions
            return [];
        }
        if (in_array($this->module_type, $trash_only_action)) {
            return [
                'trash' => __('bt.trash'),
            ];
        } else {
            $cs = [];
            foreach ($this->keyedStatuses as $k => $v) {
                $cs += ['changestatus(' . $k . ')' => 'Status to ' . $v];
            }
            $cs += ['trash' => __('bt.trash')];
            return $cs;
        }
    }

    public function setTableClass(): ?string
    {
        return 'table table-striped dataTable ';
    }

    public function changestatus($status): void
    {
        if ($this->selectedRowsQuery->count() > 0) {
            $ids = $this->selectedKeys();
            $swaldata = [
                'title'       => __('bt.bulk_change_status_record_warning'),
                'ids'         => $ids,
                'module_type' => $this->module_type,
                'route'       => $this->module_type == 'TimeTrackingProject' ? route('timeTracking.projects.bulk.status') : route(lcfirst($this->module_type) . 's.bulk.status'),
                'status'      => $status
            ];
            $this->dispatchBrowserEvent('swal:bulkConfirm', $swaldata);

        }
    }

    public function trash(): void
    {
        if ($this->module_type == 'TimeTrackingProject') {
            $route = route('timeTracking.projects.bulk.delete');
        } elseif ($this->module_type == 'Schedule' || $this->module_type == 'RecurringInvoice') {
            $route = route('scheduler.bulk.delete');
        } else {
            $route = route(lcfirst($this->module_type) . 's.bulk.delete');
        }
        if ($this->selectedRowsQuery->count() > 0) {
            $ids = $this->selectedKeys();
            $swaldata = [
                'title'   => __('bt.bulk_trash_record_warning'),
                'message' => __('bt.bulk_trash_record_warning_msg'),
                'ids'     => $ids,
                'route'   => $route,
            ];
            $this->dispatchBrowserEvent('swal:bulkConfirm', $swaldata);
        }
    }

    public function query(): Builder
    {
        if ($this->module_type == 'Client') {
            return $this->module_fullname::getSelect()
                ->leftJoin('clients_custom', 'clients_custom.client_id', '=', 'clients.id')
                ->with(['currency'])
                ->status($this->reqstatus);
        } elseif ($this->module_type == 'RecurringInvoice') {
            return $this->module_fullname::with(['client', 'activities', 'amount.recurringInvoice.currency'])->select('recurring_invoices.*', 'recurring_invoices.id as number')
                ->status($this->reqstatus)
                ->clientId(request('client'))
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'Payment') {
            return $this->module_fullname::has('client')->has('invoice')->with('client', 'invoice', 'paymentMethod');
        } elseif ($this->module_type == 'Expense') {
            return $this->module_fullname::defaultQuery()
                ->categoryId(request('category'))
                ->vendorId(request('vendor'))
                ->status($this->reqstatus)
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'TimeTrackingProject') {
            return $this->module_fullname::getSelect()
                ->with('client')
                ->companyProfileId(request('company_profile'))
                ->statusId($this->reqstatus);
        } elseif ($this->module_type == 'Purchaseorder') {
            return $this->module_fullname::with(['vendor', 'activities', 'amount.purchaseorder.currency'])->select('purchaseorders.*')
                ->status($this->reqstatus)
                ->vendorId(request('vendor'))
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'Schedule') {
            return $this->module_fullname::with(['latestOccurrence', 'category'])->where('isRecurring', '<>', '1')
                ->select('schedule.*');
        } elseif ($this->module_type == 'RecurringEvent') {
            return $this->module_fullname::with(['category'])->where('isRecurring', '=', '1')
                ->select('schedule.*');
        } elseif ($this->module_type == 'ScheduleCategory') {
            return $this->module_fullname::select('schedule_categories.*');
        } elseif ($this->module_type == 'Employee') {
            return $this->module_fullname::select('employees.*')->status($this->reqstatus);
        } elseif ($this->module_type == 'Vendor') {
            return $this->module_fullname::select('vendors.*')->status($this->reqstatus);
        } elseif ($this->module_type == 'Product') {
            return $this->module_fullname::with('category', 'vendor', 'inventorytype')
                ->with('taxRate', 'taxRate2')
                ->status($this->reqstatus);
        } elseif ($this->module_type == 'Category') {
            return $this->module_fullname::select('categories.*');
        } elseif ($this->module_type == 'ItemLookup') {
            return $this->module_fullname::select('item_lookups.*')->with('taxRate', 'taxRate2')->orderBy('resource_table', 'asc')
                ->orderBy('name', 'asc');
        } elseif ($this->module_type == 'MailQueue') {
            return $this->module_fullname::select('mail_queue.*')->orderBy('sent', 'asc');
        } elseif ($this->module_type == 'User') {
            if (auth()->user()->hasRole('superadmin')){
                return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                    ->userType(request('userType'));
            }else{
                return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                    ->role(['admin', 'user', 'client']);
            }
        } else {
            return $this->module_fullname::with(['client', 'activities', 'amount.' . lcfirst($this->module_type) . '.currency'])
                ->select(lcfirst($this->module_type) . 's.*')
                ->status($this->reqstatus)
                ->clientId(request('client'))
                ->companyProfileId(request('company_profile'));
        }
    }
}
