<?php

namespace BT\Http\Livewire\DataTables;

use BT\Modules\Clients\Models\Client;
use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\PaymentMethods\Models\PaymentMethod;
use BT\Modules\Scheduler\Models\Category;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
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
                    $col_client_vendor = Column::make(trans('bt.vendor'), 'vendor.name')
                        ->searchable()
                        ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Vendor::select('name')->whereColumn('vendor_id', 'id'), $direction))
                        ->format(fn($value, $row, Column $column) => '<a href="/vendors/' . $row->vendor->id . '">' . $value . '</a>')
                        ->html();
                    $col_invoice_id = null;
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'due_at';
                    $col_formatted_balance = Column::make(__('bt.balance'), 'amount.balance')
                        ->format(fn($value, $row, Column $column) => CurrencyFormatter::format($value, $row->currency));
                    break;
                case 'Invoice':
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->searchable()
                        ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction))
                        ->format(fn($value, $row, Column $column) => '<a href="/clients/' . $row->client->id . '">' . $value . '</a>')
                        ->html();
                    $col_invoice_id = null;
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'due_at';
                    $col_formatted_balance = Column::make(__('bt.balance'), 'amount.balance')
                        ->format(fn($value, $row, Column $column) => CurrencyFormatter::format($value, $row->currency));
                    break;
                default: //quote or workorder
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->searchable()
                        //error in WithSorting.php. need fix or pr#805
                        ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction))
                        ->format(fn($value, $row, Column $column) => '<a href="/clients/' . $row->client->id . '">' . $value . '</a>')
                        ->html();
                    $col_invoice_id = Column::make(trans('bt.converted'), 'invoice_id')
                        ->format(function ($value, $row, Column $column) {
                            $ret = '';
                            if ($row->invoice_id)
                                $ret .= '<a href="' . route('invoices.edit', [$row->invoice_id]) . '">' . trans('bt.invoice') . '</a>';
                            elseif ($row->workorder_id)
                                $ret .= '<a href="' . route('workorders.edit', [$row->workorder_id]) . '">' . trans('bt.workorder') . '</a>';
                            else
                                $ret .= trans('bt.no');
                            return $ret;
                        })
                        ->html();
                    $col_title_date_due = trans('bt.expires');
                    $col_db_date_due = 'expires_at';
                    $col_formatted_balance = null;
            }
            $default_columns = [
                Column::make(__('bt.status'), lcfirst($module_type) . '_status_id')
                    ->format(function ($value, $row, Column $column) use ($statuses) {
                        $ret = '<span class="badge badge-' . strtolower($statuses[$row->status_text]) . '">' . $statuses[$row->status_text] . '</span>';
                        if ($row->viewed)
                            $ret .= '<span class="badge bg-success">' . trans('bt.viewed') . '</span>';
                        else
                            $ret .= '<span class="badge bg-secondary">' . trans('bt.not_viewed') . '</span>';
                        return $ret;
                    })
                    ->html(),
                Column::make(trans('bt.' . lcfirst($module_type)), 'number')
                    ->searchable()
                    ->sortable()
                    ->format(fn($value, $row, Column $column) => '<a href="/' . lcfirst($module_type) . 's/' . $row->id . '/edit">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.date'), lcfirst($module_type) . '_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(lcfirst($module_type) . '_date', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->{lcfirst($module_type) . '_date'})),
                Column::make($col_title_date_due, $col_db_date_due)
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy($col_db_date_due, $direction))
                    ->format(fn($value, $row, Column $column) => $row->isOverdue ? '<div style="color: red; font-weight: bold;">' .  DateFormatter::format($row->$col_db_date_due) . '</div>': DateFormatter::format($row->$col_db_date_due))
                    ->html(),
                $col_client_vendor,
                Column::make(trans('bt.summary'), 'summary')
                    ->sortable(),
                Column::make(__('bt.total'), 'amount.total')
                    ->format(fn($value, $row, Column $column) => CurrencyFormatter::format($value, $row->currency)),
                $col_formatted_balance,
                $col_invoice_id,
                Column::make('Action')
                    ->label(fn($row, Column $column) => view(lcfirst($module_type) . 's._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Client') { //Client column defs
            $default_columns = [
                Column::make(__('bt.client'), 'name')
                    ->searchable()
                    ->sortable()
                    ->format(fn($value, $row, Column $column) => '<div title="' . $row->unique_name . '"><a href="/clients/' . $row->id . '">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.email_address'), 'email')
                    ->searchable()
                    ->sortable(),
                Column::make(trans('bt.phone_number'), 'phone')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.balance'), 'id')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('balance', $direction))
                    ->format(fn($value, $row, Column $column) => $row->formatted_balance),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make(trans('bt.created'), 'created_at')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('created_at', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->created_at)),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('clients._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'RecurringInvoice') { //RecurringInvoice column defs
            $frequencies = Frequency::lists();
            $default_columns = [
                Column::make(__('bt.number'), 'id')
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction))
                    ->format(fn($value, $row, Column $column) => '<a href="/clients/' . $row->client->id . '">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.summary'), 'summary')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('summary', $direction))
                    ->format(fn($value, $row, Column $column) => $row->formatted_summary),
                Column::make(trans('bt.next_date'), 'next_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('next_date', $direction))
                    ->format(fn($value, $row, Column $column) => $row->formatted_next_date),
                Column::make(trans('bt.stop_date'), 'stop_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('stop_date', $direction))
                    ->format(fn($value, $row, Column $column) => $row->formatted_stop_date),
                Column::make(__('bt.every'), 'recurring_frequency')
                    ->sortable()
                    ->format(fn($value, $row, Column $column) => $value . ' ' . $frequencies[$row->recurring_period]),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('recurring_invoices._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Payment') { //Payment column defs
            $default_columns = [
                Column::make(__('bt.payment_date'), 'paid_at')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('paid_at', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->paid_at)),
                Column::make(trans('bt.invoice'), 'invoice.number')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Invoice::select('number')->whereColumn('invoice_id', 'id'), $direction))
                    ->format(fn($value, $row, Column $column) => '<a href="/invoices/' . $row->invoice_id . '/edit">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.invoice_date'), 'invoice.invoice_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Invoice::select('invoice_date')->whereColumn('invoice_id', 'id'), $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->paid_at)),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction))
                    ->format(fn($value, $row, Column $column) => '<a href="/clients/' . $row->client->id . '">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.summary'), 'invoice.summary')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Invoice::select('summary')->whereColumn('invoice_id', 'id'), $direction)),
                Column::make(trans('bt.amount'), 'amount')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('amount', $direction))
                    ->format(fn($value, $row, Column $column) => CurrencyFormatter::format($row->amount)),
                Column::make(__('bt.payment_method'), 'paymentMethod.name')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(PaymentMethod::select('name')->whereColumn('payment_method_id', 'id'), $direction)),
                Column::make(__('bt.note'), 'note')
                    ->sortable(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('payments._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Expense') { //Expense column defs
            $default_columns = [
                Column::make(__('bt.date'), 'expense_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('expense_date', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->expense_date)),
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->category->name;
                        if ($row->vendor)
                            $ret .= '<br><span class="text-muted">' . $row->vendor->name . '</span>';
                        return $ret;
                    })
                    ->html(),
                Column::make(trans('bt.description'), 'description')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.amount'), 'amount')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('amount', $direction))
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->formatted_amount;
                        if ($row->is_billable)
                            if ($row->has_been_billed)
                                $ret .= '<br><a href="' . route('invoices.edit', [$row->invoice_id]) . '"><span class="badge bg-success">' . trans('bt.billed') . '</span></a>';
                            else
                                $ret .= '<br><span class="badge bg-danger">' . trans('bt.not_billed') . '</span>';
                        else
                            $ret .= '<br><span class="badge bg-secondary">' . trans('bt.not_billable') . '</span>';
                        if ($row->client)
                            $ret .= '<br><span class="text-muted">' . $row->client->name . '</span>';

                        return $ret;
                    })
                    ->html(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('expenses._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'TimeTrackingProject') { //Project column defs
            $default_columns = [
                Column::make(__('bt.project'), 'name')
                    ->searchable()
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
                    ->searchable()
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Client::select('name')->whereColumn('client_id', 'id'), $direction))
                    ->format(fn($value, $row, Column $column) => '<a href="/clients/' . $row->client->id . '">' . $value . '</a>')
                    ->html(),
                Column::make(trans('bt.status'), 'status_id')
                    ->format(fn($value, $row, Column $column) => $statuses[$row->status_text]),
                Column::make(trans('bt.created'), 'created_at')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('created_at', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->created_at)),
                Column::make(trans('bt.due_date'), 'due_at')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('due_at', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->due_at)),
                Column::make(trans('bt.unbilled_hours'))
                    ->label(fn($row, Column $column) => $row->unbilled_hours),
                Column::make(trans('bt.billed_hours'))
                    ->label(fn($row, Column $column) => $row->billed_hours),
                Column::make(trans('bt.total_hours'))
                    ->label(fn($row, Column $column) => $row->hours),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('time_tracking._actions')->withModel($row)),
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
                Column::make(trans('bt.start_date'), 'latestOccurrence.start_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('start_date', $direction))
                    ->format(fn($value, $row, Column $column) => $row->latestOccurrence->formatted_start_date),
                Column::make(__('bt.end_date'), 'latestOccurrence.end_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('end_date', $direction))
                    ->format(fn($value, $row, Column $column) => $row->latestOccurrence->formatted_end_date),
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Category::select('name')->whereColumn('category_id', 'id'), $direction)),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('partials._actions')->withModel($row)),
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
                    ->label(fn($row, Column $column) => $row->formatted_rule_start),
                Column::make(__('bt.frequency'), 'text_trans')
                    ->label(fn($row, Column $column) => $row->text_trans),
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Category::select('name')->whereColumn('category_id', 'id'), $direction)),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('partials._actions_recurr')->withModel($row)),
            ];
        } elseif ($module_type == 'ScheduleCategory') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.id'), 'id')
                    ->sortable(),
                Column::make(trans('bt.name'), 'name')
                    ->sortable(),
                Column::make(trans('bt.category_text_color'), 'text_color')
                    ->format(fn($value, $row, Column $column) => $row->text_color . '   <i class="fa fa-square fa-border" style="color:' . $row->text_color . '"></i>')
                    ->html(),
                Column::make(__('bt.category_bg_color'), 'bg_color')
                    ->format(fn($value, $row, Column $column) => $row->bg_color . '   <i class="fa fa-square fa-border" style="color:' . $row->bg_color . '"></i>')
                    ->html(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('schedulecategories._actions')->withModel($row))
                ,
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
                    ->format(fn($value, $row, Column $column) => $row->formatted_term_date),
                Column::make(__('bt.schedule'), 'schedule')
                    ->sortable()
                    ->format(fn($value, $row, Column $column) => $row->formatted_schedule),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make(__('bt.employee_driver'), 'driver')
                    ->sortable()
                    ->format(fn($value, $row, Column $column) => $row->formatted_driver),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('employees._actions')->withModel($row)),
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
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('vendors._actions')->withModel($row)),
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
                Column::make(__('bt.tax_1'), 'tax_rate_id')
                    ->format(fn($value, $row, Column $column) => $row->taxRate->name),
                Column::make(__('bt.tax_2'), 'tax_rate_2_id')
                    ->format(fn($value, $row, Column $column) => $row->taxRate2->name),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('products._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'Category') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->sortable()
                    ->searchable(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('categories._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'ItemLookup') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                    ->searchable()
                    //why not working?
                    //->sortable(fn(Builder $query, string $direction) => $query->orderBy('name', $direction))
                    ->format(fn($value, $row, Column $column) => $row->formatted_name)
                    ->html(),
                Column::make(__('bt.description'), 'description')
                    ->sortable()
                    ->searchable(),
                Column::make(__('bt.tax_1'), 'tax_rate_id')
                    ->format(fn($value, $row, Column $column) => $row->taxRate->name),
                Column::make(__('bt.tax_2'), 'tax_rate_2_id')
                    ->format(fn($value, $row, Column $column) => $row->taxRate2->name),
                Column::make(__('bt.resource_table'), 'resource_table')
                    ->sortable(),
                Column::make(__('bt.resource_id'), 'resource_id')
                    ->sortable(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('item_lookups._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'MailQueue') {
            $default_columns = [
                Column::make(__('bt.date'), 'created_at')
                    ->format(fn($value, $row, Column $column) => $row->formatted_created_at),
                Column::make(__('bt.from'), 'from')
                    ->format(fn($value, $row, Column $column) => $row->formatted_from),
                Column::make(__('bt.to'), 'to')
                    ->format(fn($value, $row, Column $column) => $row->formatted_to),
                Column::make(__('bt.cc'), 'cc')
                    ->format(fn($value, $row, Column $column) => $row->formatted_cc),
                Column::make(__('bt.bcc'), 'bcc')
                    ->format(fn($value, $row, Column $column) => $row->formatted_bcc),
                Column::make(__('bt.subject'), 'subject')
                    ->format(fn($value, $row, Column $column) => '<a href="javascript:void(0)" class="btn-show-content" data-id="' . $row->id . '">' . $row->subject . '</a>')
                    ->html(),
                Column::make(__('bt.sent'), 'sent')
                    ->format(fn($value, $row, Column $column) => $row->formatted_sent),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('mail_log._actions')->withModel($row)),
            ];
        } elseif ($module_type == 'User') {
            $default_columns = [
                Column::make(__('bt.name'), 'name')
                ,
                Column::make(__('bt.email'), 'email')
                ,
                Column::make(__('bt.acl_role'), 'name')
                    ->format(fn($value, $row, Column $column) => $row->user_role),
//                Column::make(__('bt.type'), 'user_type'),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('users._actions')->withModel($row)),
            ];
        } else {
            return [];
        }

        return $default_columns;
    }
}
