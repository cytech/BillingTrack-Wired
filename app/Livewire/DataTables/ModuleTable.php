<?php

namespace BT\Livewire\DataTables;

use BT\Modules\Categories\Models\Category;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\Statuses\DocumentStatuses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ModuleTable extends DataTableComponent
{
    public $module_type;

    public $module_fullname;

    public $keyedStatuses;

    public $reqstatus;

    public $clientid;

    public $status;

    public $core_modules = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'Recurringinvoice'];


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(config('bt.resultsPerPage'));
        $this->setSearchDebounce(500);
        //$this->setFilterLayoutSlideDown();
        $this->setFilterLayoutPopover();

        // remove search box on client/vendor view tabs
        if ($this->clientid) {
            $this->setSearchDisabled();
        }

        if (request('vendor')) {
            $vendorname = Vendor::find(request('vendor'));
            $this->setSearch($vendorname->name);
        }
        $this->setTableAttributes([
            'default' => true,
            'class'   => 'table-striped',
        ]);

        $this->setTheadAttributes([
            'default' => true,
            'class'   => 'lwtable',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id') || $column->isLabel()) {
                return [
                    'default' => true,
                    'width'   => '8%',
                ];
            }

            return [];
        });
    }

    public function mount()
    {
        $nosearch_modules = ['ScheduleCategory', 'Category', 'MailQueue', 'User'];

        if (in_array($this->module_type, $this->core_modules)) {
            if ($this->module_type == 'Workorder'){
                $this->setDefaultSort('job_date', 'desc');
            }else {
                $this->setDefaultSort('document_date', 'desc');
            }
            if ($this->module_type === 'Recurringinvoice') {
                $this->keyedStatuses = collect(('BT\\Support\\Statuses\\DocumentStatuses')::lists())->only(9, 10);
            } else {
                $this->keyedStatuses = collect(('BT\\Support\\Statuses\\DocumentStatuses')::lists())->except(4, 6, 7, 8, 9, 10);
            }
        } elseif (in_array($this->module_type, $nosearch_modules)) {
            $this->setSearchDisabled();
        } elseif ($this->module_type == 'Client') {
            $this->setDefaultSort('name');
        } elseif ($this->module_type == 'Payment') {
            // set $status from payment type (client/vendor) livewire variable or request
            $this->status ?: $this->status = request('status');
            $this->setDefaultSort('paid_at', 'desc');
        }
    }

    #[On('reset_bulk_select')]
    public function resetBulkSelect()
    {
        $this->clearSelected();
    }

    public function columns(): array
    {
        if (in_array($this->module_type, $this->core_modules)) {
            $status_model = 'BT\\Support\\Statuses\\DocumentStatuses';
        } else {
            $status_model = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';
        }
        $statuses = class_exists($status_model) ? $status_model::listsAllFlat() + ['overdue' => trans('bt.overdue')] : null;
        // send $status (client/vendor) to payment for column selection
        if ($this->module_type == 'Payment') {
            $statuses = $this->status;
        }

        return ModuleColumnDefs::columndefs($statuses, $this->module_type);
    }

    public function filters(): array
    {
        if ($this->module_type == 'TimeTrackingProject' || $this->module_type == 'Expense') {
            $statusclass = 'BT\\Support\\Statuses\\' . $this->module_type . 'Statuses';
            $options = $statusclass::listsAllFlatDT($this->module_type);
            $deffilter = $this->module_type == 'TimeTrackingProject' ? 1 : '';
            $statusidfilter = SelectFilter::make(__('bt.status'))
                ->options($options)
                ->setFilterDefaultValue($deffilter)
                ->filter(function (Builder $builder, string $value) {
                    if ($value) {
                        $builder->StatusId($value); //scope
                    }
                });
        }

        $companyfilter = SelectFilter::make(__('bt.company_profiles'), 'company_profile_id')
            ->options(['' => trans('bt.all_company_profiles')] + CompanyProfile::getList())
            ->setFilterDefaultValue('')
            ->filter(function (Builder $builder, string $value) {
                if ($value) {
                    $builder->where('company_profile_id', $value);
                }
            });

        // for 'Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'Recurringinvoice'
        // filter status passed through dashboard widget and sidebar (settings default filter)
        switch ($this->module_type) {
            case 'Quote':
            case 'Workorder':
            case 'Invoice':
            case 'Purchaseorder':
            case 'Recurringinvoice':
                $options = DocumentStatuses::listsAllFlatDT($this->module_type);
                if ($this->reqstatus != 'overdue') {
                    $reqstatus = DocumentStatuses::getStatusId($this->reqstatus);
                } else {
                    $reqstatus = 'overdue';
                }
                return [
                    SelectFilter::make(__('bt.status'))
                        ->options($options)
                        ->setFilterDefaultValue($reqstatus)
                        ->filter(function (Builder $builder, string $value) {
                            if ($value === 'overdue') {
                                $builder->Overdue();
                            } else {
                                $builder->where('document_status_id', $value);
                            }
                        }),
                    $companyfilter
                ];
            case 'TimeTrackingProject':
                return [
                    $statusidfilter,
                    $companyfilter
                ];
            case 'Expense':
                $categories = ['' => trans('bt.all_categories')] + Category::getList();
                $vendors = ['' => trans('bt.all_vendors')] + Vendor::getList();
                return [
                    $statusidfilter,
                    $companyfilter,
                    SelectFilter::make(__('bt.categories'))
                        ->options($categories)
                        ->filter(function (Builder $builder, string $value) {
                            if ($value) {
                                $builder->where('category_id', $value);
                            }
                        }),
                    SelectFilter::make(__('bt.vendors'))
                        ->options($vendors)
                        ->filter(function (Builder $builder, string $value) {
                            if ($value) {
                                $builder->where('vendor_id', $value);
                            }
                        }),
                ];
            case 'Client':
            case 'Employee':
            case 'Vendor':
            case 'Product':
                $options1 = ['' => __('bt.all_statuses'), 1 => __('bt.active'), 0 => __('bt.inactive')];
                $options2 = ['' => __('bt.all_types'), 1 => __('bt.company'), 0 => __('bt.individual')];
                $statusfilter = SelectFilter::make(__('bt.status'))
                    ->options($options1)
                    ->setFilterDefaultValue(1)
                    ->filter(function (Builder $builder, string $value) {
                        $builder->where(lcfirst($this->module_type) . 's.active', $value);
                    });
                if ($this->module_type == 'Client') {
                    return [
                        $statusfilter,
                        SelectFilter::make(__('bt.type'))
                            ->options($options2)
                            ->setFilterDefaultValue('')
                            ->filter(function (Builder $builder, string $value) {
                                $builder->where(lcfirst($this->module_type) . 's.is_company', $value);
                            }),
                    ];
                } else
                    return [
                        $statusfilter
                    ];
        }
        return [];
    }

    public function bulkActions(): array
    {
        $no_bulk_actions = ['ScheduleCategory', 'Category', 'ItemLookup', 'MailQueue', 'User'];
        $trash_only_action = ['Payment', 'Expense', 'Schedule', 'RecurringEvent'];
        $no_trash_action = ['Employee', 'Vendor', 'Product'];
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
                $cs += ['changestatus(' . $k . ')' => __('bt.status_to') . $v];
            }
            if (!in_array($this->module_type, $no_trash_action)) {
                $cs += ['trash' => __('bt.trash')];
            }

            return $cs;
        }
    }

    public function changestatus($status): void
    {
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
            if ($this->module_type == 'TimeTrackingProject') {
                $route = route('timeTracking.projects.bulk.status');
            } elseif (in_array($this->module_type, ['Client', 'Employee', 'Vendor', 'Product'])) {
                $route = route(lcfirst($this->module_type) . 's.bulk.status');
            } else {
                $route = route('documents.bulk.status');
            }

            $swaldata = [
                'title'       => __('bt.bulk_change_status_record_warning'),
                'ids'         => $ids,
                'module_type' => $this->module_type,
                'route'       => $route,
                'status'      => $status,
            ];
            $this->dispatch('swal:bulkConfirm', ...$swaldata);
        }
    }

    public function trash(): void
    {
        if ($this->module_type == 'TimeTrackingProject') {
            $route = route('timeTracking.projects.bulk.delete');
        } elseif ($this->module_type == 'Schedule' || $this->module_type == 'RecurringEvent') {
            $route = route('scheduler.bulk.delete');
        } elseif ($this->module_type == 'Payment') {
            $route = route('payments.bulk.delete');
        } elseif ($this->module_type == 'Expense') {
            $route = route('expenses.bulk.delete');
        } else {
            $route = route('documents.bulk.delete');
        }
        if ($this->getSelectedCount() > 0) {
            $ids = $this->getSelected();
            $swaldata = [
                'title'   => __('bt.bulk_trash_record_warning'),
                'message' => __('bt.bulk_trash_record_warning_msg'),
                'ids'     => $ids,
                'route'   => $route,
            ];
            $this->dispatch('swal:bulkConfirm', ...$swaldata);
        }
    }

    public function builder(): Builder
    {
        switch ($this->module_type) {
            case 'Payment':
                if ($this->clientid) {
                    return $this->module_fullname::statusId($this->status)
                        ->select(lcfirst($this->module_type) . 's.*')
                        ->where('payments.client_id', $this->clientid);
                } else {
                    return $this->module_fullname::statusId($this->status)
                        ->select(lcfirst($this->module_type) . 's.*');
                }
            case 'Schedule':
                return $this->module_fullname::where('isRecurring', '<>', '1')->select('schedule.*');
            case 'RecurringEvent':
                return $this->module_fullname::where('isRecurring', '=', '1')->select('schedule.*');
            case 'Category':
            case 'ScheduleCategory':
                return $this->module_fullname::select(Str::snake(Str::plural($this->module_type)) . '.*');
            case 'TimeTrackingProject':
            case 'Client':
            case 'Vendor':
                return $this->module_fullname::getSelect();
            case 'Expense':
            case 'Product':
            case 'Employee':
                return $this->module_fullname::select(lcfirst($this->module_type) . 's.*');
            case 'ItemLookup':
                return $this->module_fullname::select('item_lookups.*')->orderBy('resource_table', 'asc')->orderBy('name', 'asc');
            case 'MailQueue':
                return $this->module_fullname::select('mail_queue.*')->orderBy('created_at', 'desc');
            case 'User':
                if (auth()->user()->hasRole('superadmin')) {
                    return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                        ->userType(request('userType'));
                } else {
                    return $this->module_fullname::select('id', 'name', 'email', 'client_id')
                        ->role(['admin', 'user', 'client']);
                }
            default: //'Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'Recurringinvoice'
                return $this->module_fullname::select('documents.*')->where('document_type', $this->module_fullname)
                    ->clientId($this->clientid);
        }
    }
}
