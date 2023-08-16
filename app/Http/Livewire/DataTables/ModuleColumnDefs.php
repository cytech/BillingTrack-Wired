<?php

namespace BT\Http\Livewire\DataTables;

use BT\Support\Frequency;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class ModuleColumnDefs
{
    public static function columndefs($statuses, $module_type): array
    {
        $doc_modules = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder'];
        //doc_module column defs
        if ($statuses && in_array($module_type, $doc_modules)) {
            switch ($module_type) {
                case 'Purchaseorder':
                    $col_client_vendor = Column::make(__('bt.vendor'), 'vendor.name')
                        ->searchable()
                        ->sortable()
                        ->format(fn ($value, $row, Column $column) => '<a href="/vendors/'.$row->vendor->id.'">'.$value.'</a>')
                        ->html();
                    $col_invoice_id = null;
                    $col_title_date_due = __('bt.due');
                    $col_formatted_balance = Column::make(__('bt.balance'), 'amount.balance')
                        ->format(fn ($value, $row, Column $column) => $row->amount->formatted_balance);
                    break;
                case 'Invoice':
                    $col_client_vendor = Column::make(__('bt.client'), 'client.name')
                        ->searchable()
                        ->sortable()
                        ->format(fn ($value, $row, Column $column) => '<a href="/clients/'.$row->client->id.'">'.$value.'</a>')
                        ->html();
                    $col_invoice_id = null;
                    $col_title_date_due = __('bt.due');
                    $col_formatted_balance = Column::make(__('bt.balance'), 'amount.balance')
                        ->format(fn ($value, $row, Column $column) => $row->amount->formatted_balance);
                    break;
                default: //quote or workorder
                    $col_client_vendor = Column::make(__('bt.client'), 'client.name')
                        ->searchable()
                        ->sortable()
                        ->format(fn ($value, $row, Column $column) => '<a href="/clients/'.$row->client->id.'">'.$value.'</a>')
                        ->html();
                    $col_invoice_id = Column::make(__('bt.converted'), 'invoice_id')
                        ->format(function ($value, $row, Column $column) {
                            $ret = '';
                            if ($row->invoice_id) {
                                if ($row->invoice->trashed()) {
                                    $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.invoice');
                                } else {
                                    $ret .= '<a href="'.route('documents.edit', [$row->invoice_id]).'">'.__('bt.invoice').'</a>';
                                }
                            } elseif ($row->workorder_id) {
                                if ($row->workorder->trashed()) {
                                    $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.workorder');
                                } else {
                                    $ret .= '<a href="'.route('documents.edit', [$row->workorder_id]).'">'.__('bt.workorder').'</a>';
                                }
                            } else {
                                $ret .= __('bt.no');
                            }

                            return $ret;
                        })
                        ->html();
                    $col_title_date_due = __('bt.expires');
                    $col_formatted_balance = null;
            }
            $default_columns = [
                Column::make(__('bt.status'), 'document_status_id')
                    ->format(function ($value, $row, Column $column) use ($statuses) {
                        $ret = '<span class="badge badge-'.strtolower($statuses[$row->status_text]).'">'.$statuses[$row->status_text].'</span>';
                        if ($row->viewed) {
                            $ret .= '<span class="badge bg-success">'.__('bt.viewed').'</span>';
                        } else {
                            $ret .= '<span class="badge bg-secondary">'.__('bt.not_viewed').'</span>';
                        }

                        return $ret;
                    })
                    ->html(),
                Column::make(__('bt.'.lcfirst($module_type)), 'number')
                    ->searchable()
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => '<a href="/documents/'.$row->id.'/edit">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.date'), 'document_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->{'formatted_document_date'}),
                Column::make($col_title_date_due, 'action_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->isOverdue ? '<div style="color: red; font-weight: bold;">'.$row->formatted_action_date.'</div>' : $row->formatted_action_date)
                    ->html(),
                $col_client_vendor,
                Column::make(__('bt.summary'), 'summary')
                    ->sortable(),
                Column::make(__('bt.total'), 'amount.total')
                    ->format(fn ($value, $row, Column $column) => $row->amount->formatted_total),
                $col_formatted_balance,
                $col_invoice_id,
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('documents._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Client') { //Client column defs
            $default_columns = [
                Column::make(__('bt.client'), 'name')
                    ->searchable()
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => '<div title="'.$row->unique_name.'"><a href="/clients/'.$row->id.'">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.email_address'), 'email')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.phone_number'), 'phone')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.balance'), 'id') //dummy
                    ->sortable(fn (Builder $query, string $direction) => $query->orderBy('balance', $direction))
                    ->format(fn ($value, $row, Column $column) => $row->formatted_balance),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make(__('bt.created'), 'created_at')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_created_at),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('clients._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Recurringinvoice') { //Recurringinvoice column defs
            $frequencies = Frequency::lists();
            $default_columns = [
                Column::make(__('bt.number'), 'number')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => '<a href="/documents/'.$row->id.'/edit">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.status'), 'document_status_id')
                    ->format(function ($value, $row, Column $column) use ($statuses) {
                        $ret = '<span class="badge badge-'.strtolower($statuses[$row->status_text]).'">'.$statuses[$row->status_text].'</span>';

                        return $ret;
                    })
                    ->html(),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => '<a href="/clients/'.$row->client->id.'">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.summary'), 'summary')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_summary),
                Column::make(__('bt.next_date'), 'next_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_next_date),
                Column::make(__('bt.stop_date'), 'stop_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_stop_date),
                Column::make(__('bt.every'), 'recurring_frequency')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $value.' '.$frequencies[$row->recurring_period]),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('documents._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Payment') { //Payment column defs
            if ($statuses == 2) {
                $col_client_vendor = Column::make(__('bt.client_vendor'), 'vendor.name')
                    ->searchable()
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = '';
                        if ($row->vendor) {
                            if ($row->vendor->trashed()) {
                                $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.vendor');
                            } else {
                                $ret .= '<a href="/vendors/'.$row->vendor->id.'">'.$row->vendor->name.'</a>';
                            }
                        } elseif ($row->client_id == -1) {
                            $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.vendor');
                        }

                        return $ret;
                    })
                    ->html();
            } else {
                $col_client_vendor = Column::make(__('bt.client_vendor'), 'client.name')
                    ->searchable()
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = '';
                        if ($row->client) {
                            if ($row->client->trashed()) {
                                $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.client');
                            } else {
                                $ret .= '<a href="/clients/'.$row->client->id.'">'.$row->client->name.'</a>';
                            }
                        } elseif ($row->client_id == 0) {
                            $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.client');
                        }

                        return $ret;
                    })
                    ->html();
            }
            $default_columns = [
                Column::make(__('bt.payment_date'), 'paid_at')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_paid_at),
                Column::make(__('bt.document'), 'document.number')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = '';
                        if (! is_null($row->invoice_id)) {
                            if ($row->invoice) {
                                if ($row->invoice->trashed()) {
                                    $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.invoice');
                                } else {
                                    $ret .= '<a href="'.route('documents.edit', [$row->invoice_id]).'">'.$value.'</a>';
                                }
                            } elseif ($row->purchaseorder) {
                                if ($row->purchaseorder->trashed()) {
                                    $ret .= ' <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.purchaseorder');
                                } else {
                                    $ret .= '<a href="'.route('documents.edit', [$row->invoice_id]).'">'.$value.'</a>';
                                }
                            } elseif ($row->invoice_id == 0) {
                                $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.invoice');
                            } elseif ($row->invoice_id == -1) {
                                $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.purchaseorder');
                            }
                        }

                        return $ret;
                    })
                    ->html(),
                Column::make(__('bt.document_date'), 'document.document_date')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = '';
                        if ($row->invoice_id == 0) {
                            $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.invoice');
                        } elseif ($row->invoice_id == -1) {
                            $ret .= ' <span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.purchaseorder');
                        } elseif (! is_null($row->invoice_id)) {
                            $ret = $row->document->formatted_document_date;
                        }

                        return $ret;
                    })->html(),
                $col_client_vendor,
                Column::make(__('bt.summary'), 'invoice.summary')
                    ->sortable(),
                Column::make(__('bt.amount'), 'amount')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_amount),
                Column::make(__('bt.payment_method'), 'paymentMethod.name')
                    ->sortable(),
                Column::make(__('bt.note'), 'note')
                    ->sortable(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('payments._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Expense') { //Expense column defs
            $default_columns = [
                Column::make(__('bt.date'), 'expense_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_expense_date),
                Column::make(__('bt.category'), 'category.name')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->category->name;
                        if ($row->vendor) {
                            $ret .= '<br><span class="text-muted">'.$row->vendor->name.'</span>';
                        }

                        return $ret;
                    })
                    ->html(),
                Column::make(__('bt.description'), 'description')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.amount'), 'amount')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->formatted_amount;
                        if ($row->is_billable) {
                            if ($row->has_been_billed) {
                                if ($row->invoice_id == 0) {
                                    $ret .= '<br><span class="badge bg-danger" title="'.__('bt.deleted').'">'.__('bt.billed').'</span></a>';
                                } elseif ($row->invoice->trashed()) {
                                    $ret .= '<br> <span class="badge bg-danger" title="'.__('bt.trashed').'">'.__('bt.billed').'</span>';
                                } else {
                                    $ret .= '<br><a href="'.route('documents.edit', [$row->invoice_id]).'"><span class="badge bg-success">'.__('bt.billed').'</span></a>';
                                }
                            } else {
                                $ret .= '<br><span class="badge bg-info">'.__('bt.not_billed').'</span>';
                            }
                        } else {
                            $ret .= '<br><span class="badge bg-secondary">'.__('bt.not_billable').'</span>';
                        }
                        if ($row->client) {
                            $ret .= '<br><span class="text-muted">'.$row->client->name.'</span>';
                        }

                        return $ret;
                    })
                    ->html(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('expenses._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'TimeTrackingProject') { //Project column defs
            $default_columns = [
                Column::make(__('bt.project'), 'name')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => '<a href="/clients/'.$row->client->id.'">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.status'), 'status_id')
                    ->format(fn ($value, $row, Column $column) => $statuses[$row->status_text]),
                Column::make(__('bt.created'), 'created_at')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_created_at),
                Column::make(__('bt.due_date'), 'due_at')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_due_at),
                Column::make(__('bt.unbilled_hours'))
                    ->label(fn ($row, Column $column) => $row->unbilled_hours),
                Column::make(__('bt.billed_hours'))
                    ->label(fn ($row, Column $column) => $row->billed_hours),
                Column::make(__('bt.total_hours'))
                    ->label(fn ($row, Column $column) => $row->hours),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('time_tracking._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Schedule') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.location'), 'location_str')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.description'), 'description')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.start_date'), 'latestOccurrence.start_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->latestOccurrence->formatted_start_date),
                Column::make(__('bt.end_date'), 'latestOccurrence.end_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->latestOccurrence->formatted_end_date),
                Column::make(__('bt.category'), 'category.name')
                    ->sortable(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('partials._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'RecurringEvent') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.location'), 'location_str')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.start_date'), 'formatted_rule_start')
                    ->label(fn ($row, Column $column) => $row->formatted_rule_start),
                Column::make(__('bt.frequency'), 'text_trans')
                    ->label(fn ($row, Column $column) => $row->text_trans),
                Column::make(__('bt.category'), 'category.name')
                    ->sortable(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('partials._actions_recurr')->withModel($row)),
            ];
        } elseif ($module_type == 'ScheduleCategory') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.id'), 'id')
                    ->sortable(),
                Column::make(__('bt.name'), 'name')
                    ->sortable(),
                Column::make(__('bt.category_text_color'), 'text_color')
                    ->format(fn ($value, $row, Column $column) => $row->text_color.'   <i class="fa fa-square fa-border" style="color:'.$row->text_color.'"></i>')
                    ->html(),
                Column::make(__('bt.category_bg_color'), 'bg_color')
                    ->format(fn ($value, $row, Column $column) => $row->bg_color.'   <i class="fa fa-square fa-border" style="color:'.$row->bg_color.'"></i>')
                    ->html(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('schedulecategories._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Employee') {
            $default_columns = [
                Column::make(__('bt.number'), 'number')
                    ->sortable(),
                Column::make(__('bt.name'), 'full_name')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.employee_title'), 'title')
                    ->sortable(),
                Column::make(__('bt.employee_billing_rate'), 'billing_rate')
                    ->sortable(),
                Column::make(__('bt.type'), 'type.name')
                    ->sortable(),
                Column::make(__('bt.term_date'), 'term_date')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_term_date),
                Column::make(__('bt.schedule'), 'schedule')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_schedule),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make(__('bt.employee_driver'), 'driver')
                    ->sortable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_driver),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('employees._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Vendor') {
            $default_columns = [
                Column::make(__('bt.vendor_name'), 'name')
                    ->sortable()
                    ->searchable()
                    ->format(fn ($value, $row, Column $column) => '<div title="'.$row->name.'"><a href="/vendors/'.$row->id.'">'.$value.'</a>')
                    ->html(),
                Column::make(__('bt.email_address'), 'email')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.phone_number'), 'phone')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.balance'), 'id') //dummy
                    ->sortable(fn (Builder $query, string $direction) => $query->orderBy('balance', $direction))
                    ->format(fn ($value, $row, Column $column) => $row->formatted_balance),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('vendors._actions')->withModel($row)),
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
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->inventorytype->name;
                        if ($row->is_trackable) {
                            $ret = '<div class="bg-secondary" title="'.__('bt.trackable').'">'.$row->inventorytype->name.'</div>';
                        }

                        return $ret;
                    })
                    ->html(),
                Column::make(__('bt.product_numstock'), 'numstock')
                    ->sortable(),
                Column::make(__('bt.tax_1'), 'tax_rate_id')
                    ->format(fn ($value, $row, Column $column) => $row->taxRate->name),
                Column::make(__('bt.tax_2'), 'tax_rate_2_id')
                    ->format(fn ($value, $row, Column $column) => $row->taxRate2->name),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('products._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Category') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->sortable()
                    ->searchable(),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('categories._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'ItemLookup') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->searchable()
                    ->format(fn ($value, $row, Column $column) => $row->formatted_name)
                    ->html(),
                Column::make(__('bt.description'), 'description')
                    ->searchable(),
                Column::make(__('bt.tax_1'), 'tax_rate_id')
                    ->format(fn ($value, $row, Column $column) => $row->taxRate->name),
                Column::make(__('bt.tax_2'), 'tax_rate_2_id')
                    ->format(fn ($value, $row, Column $column) => $row->taxRate2->name),
                Column::make(__('bt.resource_table'), 'resource_table'),
                Column::make(__('bt.resource_id'), 'resource_id'),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('item_lookups._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'MailQueue') {
            $default_columns = [
                Column::make(__('bt.date'), 'created_at')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_created_at),
                Column::make(__('bt.from'), 'from')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_from),
                Column::make(__('bt.to'), 'to')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_to),
                Column::make(__('bt.cc'), 'cc')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_cc),
                Column::make(__('bt.bcc'), 'bcc')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_bcc),
                Column::make(__('bt.subject'), 'subject')
                    ->format(fn ($value, $row, Column $column) => '<a href="javascript:void(0)" class="btn-show-content" data-id="'.$row->id.'">'.$row->subject.'</a>')
                    ->html(),
                Column::make(__('bt.sent'), 'sent')
                    ->format(fn ($value, $row, Column $column) => $row->formatted_sent),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('mail_log._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'User') {
            $default_columns = [
                Column::make(__('bt.name'), 'name'),
                Column::make(__('bt.email'), 'email'),
                Column::make(__('bt.acl_role'), 'name')
                    ->format(fn ($value, $row, Column $column) => $row->user_role),
                Column::make('Action')
                    ->label(fn ($row, Column $column) => view('users._actions')->withModel($row)),
            ];
        } else {
            return [];
        }

        return $default_columns;
    }
}
