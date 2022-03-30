<?php

namespace BT\Http\Livewire\DataTables;

use BT\Modules\Clients\Models\Client;
use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\PaymentMethods\Models\PaymentMethod;
use BT\Modules\Scheduler\Models\Category;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\Frequency;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ModuleColumnDefs
{
    public static function columndefs($statuses, $module_type): array
    {
        $doc_modules = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder'];
        //doc_module column defs
        if ($statuses && in_array($module_type, $doc_modules)) {
            switch ($module_type) {
                case 'Purchaseorder':
                    $col_client_vendor = Column::make(trans('bt.vendor'), 'vendor.name')
                        ->searchable()
                        ->sortable(function ($query, $direction) {
                            return $query->orderBy(Vendor::select('name')->whereColumn('vendor_id', 'id'), $direction);
                        })
                        ->format(function ($value, $column, $row) {
                            return '<a href="/vendors/' . $row->vendor->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'formatted_due_at';
                    break;
                case 'Invoice':
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->searchable()
                        ->sortable(function ($query, $direction) {
                            return $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction);
                        })
                        ->format(function ($value, $column, $row) {
                            return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'formatted_due_at';
                    break;
                default:
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->searchable()
                        ->sortable(function ($query, $direction) {
                            return $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction);
                        })
                        ->format(function ($value, $column, $row) {
                            return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.expires');
                    $col_db_date_due = 'formatted_expires_at';
            }
            $default_columns = [
                Column::make(__('bt.status'), 'status_text')
                    ->format(function ($value, $column, $row) use ($statuses) {
                        $ret = '<span class="badge badge-' . strtolower($value) . '">' . $statuses[$value] . '</span>';
                        if ($row->viewed)
                            $ret .= '<span class="badge bg-success">' . trans('bt.viewed') . '</span>';
                        else
                            $ret .= '<span class="badge bg-secondary">' . trans('bt.not_viewed') . '</span>';
                        return $ret;
                    })
                    ->asHtml(),
                Column::make(trans('bt.' . lcfirst($module_type)), 'number')
                    ->searchable()
                    ->sortable()
                    ->format(function ($value, $column, $row) use ($module_type) {
                        return '<a href="/' . lcfirst($module_type) . 's/' . $row->id . '/edit">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.date'), 'formatted_' . lcfirst($module_type) . '_date')
                    ->sortable(function ($query, $direction) use ($module_type) {
                        return $query->orderBy(lcfirst($module_type) . '_date', $direction);
                    }),
                Column::make($col_title_date_due, $col_db_date_due)
                    ->sortable(function ($query, $direction) use ($module_type, $col_db_date_due) {
                        return $query->orderBy(substr($col_db_date_due, strpos($col_db_date_due, '_') + 1), $direction);
                    }),
                $col_client_vendor,
                Column::make(trans('bt.summary'), 'summary')
                    ->sortable(),
                Column::make(__('bt.total'), 'amount.formatted_total'),
                Column::make(__('bt.balance'), 'amount.formatted_balance')->hideIf($module_type <> 'Purchaseorder' && $module_type <> 'Invoice'),
                Column::make(trans('bt.converted'), 'invoice_id')
                    ->format(function ($value, $column, $row) {
                        $ret = '';
                        if ($row->invoice_id)
                            $ret .= '<a href="' . route('invoices.edit', [$row->invoice_id]) . '">' . trans('bt.invoice') . '</a>';
                        elseif ($row->workorder_id)
                            $ret .= '<a href="' . route('workorders.edit', [$row->workorder_id]) . '">' . trans('bt.workorder') . '</a>';
                        else
                            $ret .= trans('bt.no');
                        return $ret;
                    })
                    ->asHtml()
                    ->hideIf($module_type <> 'Quote' && $module_type <> 'Workorder'),
                Column::make('Action')
                    ->format(function ($value, $column, $row) use ($module_type) {
                        return view(lcfirst($module_type) . 's._actions')->withModel($row);
                    }),
            ];
        } elseif ($module_type == 'Client') { //Client column defs
            $default_columns = [
                Column::make(__('bt.client'), 'name')
                    ->searchable()
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        return '<div title="' . $row->unique_name . '"><a href="/clients/' . $row->id . '">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.email_address'), 'email')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.phone_number'), 'phone')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.balance'), 'formatted_balance')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('balance', $direction);
                    }),
                Column::make(__('bt.active'), 'active')
                    ->sortable(),
                Column::make(trans('bt.created'), 'formatted_created_at')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('created_at', $direction);
                    }),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('clients._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'RecurringInvoice') { //RecurringInvoice column defs
            $frequencies = Frequency::lists();
            $default_columns = [
                Column::make(__('bt.number'), 'id')
                    ->sortable()
                    ->addAttributes(['width' => '5%']),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction);
                    })
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.summary'), 'formatted_summary')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('summary', $direction);
                    }),
                Column::make(trans('bt.next_date'), 'formatted_next_date')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('next_date', $direction);
                    }),
                Column::make(trans('bt.stop_date'), 'formatted_stop_date')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('stop_date', $direction);
                    }),
                Column::make(__('bt.every'), 'recurring_frequency')
                    ->sortable()
                    ->format(function ($value, $column, $row) use ($frequencies) {
                        return $value . ' ' . $frequencies[$row->recurring_period];
                    })
                    ->
                    asHtml(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('recurring_invoices._actions')->withModel($row);
                    }),
            ];
        } elseif ($module_type == 'Payment') { //Payment column defs
            $default_columns = [
                Column::make(__('bt.payment_date'), 'formatted_paid_at')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('paid_at', $direction);
                    }),
                Column::make(trans('bt.invoice'), 'invoice.number')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Invoice::select('number')->whereColumn('invoice_id', 'id'), $direction);
                    })
                    ->format(function ($value, $column, $row) {
                        return '<a href="/invoices/' . $row->invoice_id . '/edit">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.invoice_date'), 'invoice.formatted_invoice_date')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Invoice::select('invoice_date')->whereColumn('invoice_id', 'id'), $direction);
                    }),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction);
                    })
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.summary'), 'invoice.formatted_summary')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Invoice::select('summary')->whereColumn('invoice_id', 'id'), $direction);
                    }),
                Column::make(trans('bt.amount'), 'formatted_amount')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('amount', $direction);
                    }),
                Column::make(__('bt.payment_method'), 'paymentMethod.name')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(PaymentMethod::select('name')->whereColumn('payment_method_id', 'id'), $direction);
                    }),
                Column::make(__('bt.note'), 'note')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('payments._actions')->withModel($row);
                    }),
            ];
        } elseif ($module_type == 'Expense') { //Expense column defs
            $default_columns = [
                Column::make(__('bt.date'), 'formatted_expense_date')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('expense_date', $direction);
                    }),
                Column::make(trans('bt.category'), 'category_name')
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        $ret = $row->category_name;
                        if ($row->vendor_name)
                            $ret .= '<br><span class="text-muted">' . $row->vendor_name . '</span>';
                        return $ret;
                    })
                    ->asHtml(),
                Column::make(trans('bt.description'), 'description')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.amount'), 'formatted_amount')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('amount', $direction);
                    })
                    ->format(function ($value, $column, $row) {
                        $ret = $row->formatted_amount;
                        if ($row->is_billable)
                            if ($row->has_been_billed)
                                $ret .= '<br><a href="' . route('invoices.edit', [$row->invoice_id]) . '"><span class="badge bg-success">' . trans('bt.billed') . '</span></a>';
                            else
                                $ret .= '<br><span class="badge bg-danger">' . trans('bt.not_billed') . '</span>';
                        else
                            $ret .= '<br><span class="badge bg-secondary">' . trans('bt.not_billable') . '</span>';

                        return $ret;
                    })
                    ->asHtml(),
                Column::make(trans('bt.attachments'), 'expense.attachments')
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('expenses._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'TimeTrackingProject') { //Project column defs
            $default_columns = [
                Column::make(__('bt.project'), 'name')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction);
                    })
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml(),
                Column::make(trans('bt.status'), 'status_text')
                ,
                Column::make(trans('bt.created'), 'formatted_created_at')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('created_at', $direction);
                    }),
                Column::make(trans('bt.due_date'), 'formatted_due_at')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy('due_at', $direction);
                    }),
                Column::make(trans('bt.unbilled_hours'), 'unbilled_hours')
                    ->sortable(),
                Column::make(__('bt.billed_hours'), 'billed_hours')
                    ->sortable(),
                Column::make(__('bt.total_hours'), 'hours')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('time_tracking._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'Schedule') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.location'), 'location_str')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.description'), 'description')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.start_date'), 'latestOccurrence.formatted_start_date')
                ,
                Column::make(__('bt.end_date'), 'latestOccurrence.formatted_end_date')
                ,
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Category::select('name')->whereColumn('category_id', 'id'), $direction);
                    }),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('partials._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'RecurringEvent') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.location'), 'location_str')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.start_date'), 'formatted_rule_start')
                ,
                Column::make(__('bt.frequency'), 'text_trans')
                ,
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable(function ($query, $direction) {
                        return $query->orderBy(Category::select('name')->whereColumn('category_id', 'id'), $direction);
                    }),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('partials._actions_recurr')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'ScheduleCategory') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.id'), 'id')
                    ->sortable(),
                Column::make(trans('bt.name'), 'name')
                    ->sortable(),
                Column::make(trans('bt.category_text_color'), 'text_color')
                    ->format(function ($value, $column, $row) {
                        return $row->text_color . '   <i class="fa fa-square fa-border" style="color:' . $row->text_color . '"></i>';
                    })
                    ->asHtml(),
                Column::make(__('bt.category_bg_color'), 'bg_color')
                    ->format(function ($value, $column, $row) {
                        return $row->bg_color . '   <i class="fa fa-square fa-border" style="color:' . $row->bg_color . '"></i>';
                    })
                    ->asHtml(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('schedulecategories._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'Employee') {
            $default_columns = [
                Column::make(__('bt.id'), 'id')
                    ->sortable(),
                Column::make(__('bt.employee_number'), 'number')
                    ->sortable(),
                Column::make(__('bt.first_name'), 'first_name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.last_name'), 'last_name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.employee_short_name'), 'short_name')
                    ->sortable(),
                Column::make(__('bt.employee_title'), 'title')
                    ->sortable(),
                Column::make(__('bt.employee_billing_rate'), 'billing_rate')
                    ->sortable(),
                Column::make(__('bt.employees_scheduled'), 'schedule')
                    ->sortable(),
                Column::make(__('bt.active'), 'active')
                    ->sortable(),
                Column::make(__('bt.employee_driver'), 'driver')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('employees._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'Vendor') {
            $default_columns = [
                Column::make(__('bt.vendor_name'), 'name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.email_address'), 'email')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.phone_number'), 'phone')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.active'), 'active')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('vendors._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'Product') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.price_sales'), 'price')
                    ->sortable(),
                Column::make(__('bt.vendor'), 'vendor.name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.product_cost'), 'cost')
                    ->sortable(),
                Column::make(__('bt.category'), 'category.name')
                    ->sortable(),
                Column::make(__('bt.product_type'), 'inventorytype.name')
                    ->sortable(),
                Column::make(__('bt.product_numstock'), 'numstock')
                    ->sortable(),
                Column::make(__('bt.tax_1'), 'taxRate.name')
                ,
                Column::make(__('bt.tax_2'), 'taxRate2.name')
                ,
                Column::make(__('bt.active'), 'active')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('products._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'Category') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->sortable()
                    ->searchable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('categories._actions')->withModel($row);
                    })
                    ->addAttributes(['width' => '10%']),
            ];
        } elseif ($module_type == 'ItemLookup') {
            $default_columns = [
                Column::make(__('bt.name'), 'formatted_name')
                    ->asHtml()
//                    ->searchable()
//                    ->sortable(function ($query, $direction) {
//                        return $query->orderBy('name', $direction);
//                    })
                ,
                Column::make(__('bt.description'), 'description')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.tax_1'), 'taxRate.name')
                ,
                Column::make(__('bt.tax_2'), 'taxRate2.name')
                ,
                Column::make(__('bt.resource_table'), 'resource_table')
                    ->sortable(),
                Column::make(__('bt.resource_id'), 'resource_id')
                    ->sortable(),
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('item_lookups._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'MailQueue') {
            $default_columns = [
                Column::make(__('bt.date'), 'formatted_created_at')
                ,
                Column::make(__('bt.from'), 'formatted_from')
                ,
                Column::make(__('bt.to'), 'formatted_to')
                ,
                Column::make(__('bt.cc'), 'formatted_cc')
                ,
                Column::make(__('bt.bcc'), 'formatted_bcc')
                ,
                Column::make(__('bt.subject'), 'subject')
                    ->format(function ($value, $column, $row) {
                        return '<a href="javascript:void(0)" class="btn-show-content" data-id="' . $row->id . '">' . $row->subject . '</a>';
                    })
                    ->asHtml()
                ,
                Column::make(__('bt.sent'), 'formated_sent')
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('mail_log._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'User') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                ,
                Column::make(__('bt.email'), 'email')
                ,
                Column::make(__('bt.acl_role'), 'user_role')
                ,
//                Column::make(__('bt.type'), 'user_type')
//                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('users._actions')->withModel($row);
                    })
                    ->addAttributes(['width' => '10%']),
            ];
        } else {
            return [];
        }

        return $default_columns;
    }
}
