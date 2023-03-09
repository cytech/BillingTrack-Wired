<?php

namespace BT\Http\Livewire\DataTables;

use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Vendors\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ModuleTable extends DataTableComponent
{
    public $module_type, $module_fullname, $keyedStatuses, $reqstatus, $clientid;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setSearchDebounce(500);
        //$this->setFilterLayoutSlideDown();
        $this->setFilterLayoutPopover();
        if ($this->clientid) {
            $clientname = Client::find($this->clientid);
            $this->setSearch($clientname->name);
        }
        if (request('vendor')) {
            $vendorname = Vendor::find(request('vendor'));
            $this->setSearch($vendorname->name);
        }
        $this->setTableAttributes([
            'default' => true,
            'class'   => 'datatable',
        ]);

        $this->setTheadAttributes([
            'default' => true,
            'class'   => 'bg-body lwtable',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id') || $column->isLabel()) {
                return [
                    'default' => true,
                    'width'   => '8%'
                ];
            }

            return [];
        });
    }

    protected $listeners = ['reset_bulk_select' => 'resetBulkSelect'];  //emit from _js_global swal:bulkConfirm

    public function mount()
    {
        if ($this->module_type == 'TimeTrackingProject') {
            $this->module_fullname = 'BT\\Modules\\TimeTracking\\Models\\TimeTrackingProject';
        } elseif ($this->module_type == 'Schedule' || $this->module_type == 'RecurringEvent') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Schedule';
        } elseif ($this->module_type == 'ScheduleCategory') {
            $this->module_fullname = 'BT\\Modules\\Scheduler\\Models\\Category';
            $this->setSearchDisabled();
        } elseif ($this->module_type == 'Employee') {
            $this->module_fullname = 'BT\\Modules\\Employees\\Models\\Employee';
        } elseif ($this->module_type == 'Vendor') {
            $this->module_fullname = 'BT\\Modules\\Vendors\\Models\\Vendor';
        } elseif ($this->module_type == 'Product') {
            $this->module_fullname = 'BT\\Modules\\Products\\Models\\Product';
        } elseif ($this->module_type == 'Category') {
            $this->module_fullname = 'BT\\Modules\\Categories\\Models\\Category';
            $this->setSearchDisabled();
        } elseif ($this->module_type == 'ItemLookup') {
            $this->module_fullname = 'BT\\Modules\\ItemLookups\\Models\\ItemLookup';
        } elseif ($this->module_type == 'MailQueue') {
            $this->module_fullname = 'BT\\Modules\\MailQueue\\Models\\MailQueue';
            $this->setSearchDisabled();
        } elseif ($this->module_type == 'User') {
            $this->module_fullname = 'BT\\Modules\\Users\\Models\\User';
            $this->setSearchDisabled();
        } elseif ($this->module_type == 'Client') {
            $this->setDefaultSort('name');
            $this->module_fullname = 'BT\\Modules\\Clients\\Models\\Client';
        } elseif ($this->module_type == 'RecurringInvoice') {
            $this->setDefaultSort('client_id');
            $this->module_fullname = 'BT\\Modules\\RecurringInvoices\\Models\\RecurringInvoice';
        } elseif ($this->module_type == 'Payment') {
            $this->setDefaultSort('paid_at');
            $this->module_fullname = 'BT\\Modules\\Payments\\Models\\Payment';
        } else { //quote, workorder, invoice, purchaseorder
            $this->setDefaultSort(lcfirst($this->module_type) . '_date', 'desc');
            if (in_array($this->module_type, ['Invoice', 'Quote', 'Workorder', 'Purchaseorder'])) {
                $this->keyedStatuses = collect(('BT\\Support\\Statuses\\' . $this->module_type . 'Statuses')::lists())->except(4);
            }
            $this->module_fullname = 'BT\\Modules\\' . $this->module_type . 's\\Models\\' . $this->module_type;
        }
    }

    public function resetBulkSelect()
    {
        $this->clearSelected();
    }

    public function columns(): array
    {
        $status_model = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';
        $statuses = class_exists($status_model) ? $status_model::listsAllFlat() + ['overdue' => trans('bt.overdue')] : null;
        return ModuleColumnDefs::columndefs($statuses, $this->module_type);
    }

    public function filters(): array
    {
        //filters only applied to 'Invoice', 'Quote', 'Workorder'
        if (in_array($this->module_type, ['Invoice', 'Quote', 'Workorder'])) {
            $status_model = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';

            return [
                SelectFilter::make(__('bt.status'))
                    ->options($status_model::listsAllFlatDT($this->module_type))
                    ->filter(function (Builder $builder, string $value) {
                        if ($value === 'draft') {
                            $builder->where(lcfirst($this->module_type) . '_status_id', 1);
                        } elseif ($value === 'sent') {
                            $builder->where(lcfirst($this->module_type) . '_status_id', 2);
                        } elseif ($value === 'paid') {
                            $builder->where(lcfirst($this->module_type) . '_status_id', 3);
                        } elseif ($value === 'canceled') {
                            //cancelled status_id different in invoice
                            if ($this->module_type === 'Invoice'){
                                $builder->where(lcfirst($this->module_type) . '_status_id', 4);
                            } else{
                                $builder->where(lcfirst($this->module_type) . '_status_id', 5);
                            }
                        } elseif ($value === 'approved') {
                            $builder->where(lcfirst($this->module_type) . '_status_id', 3);
                        } elseif ($value === 'rejected') {
                            $builder->where(lcfirst($this->module_type) . '_status_id', 4);
                        } elseif ($value === 'overdue') {
                            $builder->Overdue();
                        }
                    }),
                SelectFilter::make(__('bt.company_profiles'), 'company_profile_id')
                    ->options(['' => trans('bt.all_company_profiles')] + CompanyProfile::getList())
                    ->filter(function (Builder $builder, string $value) {
                        if ($value) {
                            $builder->where('company_profile_id', $value);
                        }
                    })
            ];
        }
        return [];
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

    public function changestatus($status): void
    {
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
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
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
            $swaldata = [
                'title'   => __('bt.bulk_trash_record_warning'),
                'message' => __('bt.bulk_trash_record_warning_msg'),
                'ids'     => $ids,
                'route'   => $route,
            ];
            $this->dispatchBrowserEvent('swal:bulkConfirm', $swaldata);
        }
    }

    public function builder(): Builder
    {
        if ($this->module_type == 'RecurringInvoice') {
            return $this->module_fullname::select('recurring_invoices.*', 'recurring_invoices.id as number')
                ->status(request('status'))
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'Purchaseorder') {
            return $this->module_fullname::select('purchaseorders.*')
                ->status(request('status'))
                ->vendorId(request('vendor'))
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'Payment') {
            return $this->module_fullname::has('client')->has('invoice')
                ->select(lcfirst($this->module_type) . 's.*');
        } elseif ($this->module_type == 'Expense') {
            return $this->module_fullname::select(lcfirst($this->module_type) . 's.*')
                ->categoryId(request('category'))
                ->vendorId(request('vendor'))
                ->status(request('status'))
                ->companyProfileId(request('company_profile'));
        } elseif ($this->module_type == 'TimeTrackingProject') {
            return $this->module_fullname::companyProfileId(request('company_profile'))
                ->statusId(request('status'))
                ->getSelect();
        } elseif ($this->module_type == 'Schedule') {
            return $this->module_fullname::where('isRecurring', '<>', '1')->select('schedule.*');
        } elseif ($this->module_type == 'RecurringEvent') {
            return $this->module_fullname::where('isRecurring', '=', '1')->select('schedule.*');
        } elseif ($this->module_type == 'ScheduleCategory') {
            return $this->module_fullname::select('schedule_categories.*');
        } elseif ($this->module_type == 'Client') {
            return $this->module_fullname::getSelect()->status(request('status'));
        } elseif ($this->module_type == 'Employee') {
            return $this->module_fullname::select('employees.*')->status(request('status'));
        } elseif ($this->module_type == 'Vendor') {
            return $this->module_fullname::select('vendors.*')->status(request('status'));
        } elseif ($this->module_type == 'Product') {
            return $this->module_fullname::select('products.*')->status(request('status'));
        } elseif ($this->module_type == 'Category') {
            return $this->module_fullname::select('categories.*');
        } elseif ($this->module_type == 'ItemLookup') {
            return $this->module_fullname::select('item_lookups.*')->orderBy('resource_table', 'asc')->orderBy('name', 'asc');
        } elseif ($this->module_type == 'MailQueue') {
            return $this->module_fullname::select('mail_queue.*')->orderBy('sent', 'asc');
        } elseif ($this->module_type == 'User') {
            if (auth()->user()->hasRole('superadmin')) {
                return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                    ->userType(request('userType'));
            } else {
                return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                    ->role(['admin', 'user', 'client']);
            }
        } else { //quotes, workorders, invoices
            if ($this->reqstatus) $this->setFilter(snake_case(__('bt.status')), $this->reqstatus);
            return $this->module_fullname::
            select(lcfirst($this->module_type) . 's.*')
                ->clientId($this->clientid)
                ->companyProfileId(request('company_profile'));
        }
    }
}
