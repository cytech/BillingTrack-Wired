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

class TrashColumnDefs
{
    public static function columndefs($statuses, $module_type): array
    {
        $doc_modules = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder'];
        //doc_module column defs
        if ($statuses && in_array($module_type, $doc_modules)) {
            switch ($module_type) {
                case 'Purchaseorder':
                    $col_client_vendor = Column::make(trans('bt.vendor'), 'vendor.name')
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
                Column::make(trans('bt.' . lcfirst($module_type)), 'number'),
                Column::make(trans('bt.date'), lcfirst($module_type) . '_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(lcfirst($module_type) . '_date', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->{lcfirst($module_type) . '_date'})),
                Column::make($col_title_date_due, $col_db_date_due)
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy($col_db_date_due, $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->$col_db_date_due)),
                $col_client_vendor,
                Column::make(trans('bt.summary'), 'summary')
                    ->sortable(),
                Column::make(__('bt.total'), 'amount.total')
                    ->format(fn($value, $row, Column $column) => CurrencyFormatter::format($value, $row->currency)),
                $col_formatted_balance,
                $col_invoice_id,
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } elseif (!$statuses && $module_type == 'Client') { //Client column defs
            $default_columns = [
                Column::make(__('bt.client'), 'name')
                    ->sortable(),
                Column::make(trans('bt.email_address'), 'email')
                    ->sortable(),
                Column::make(trans('bt.phone_number'), 'phone')
                    ->sortable(),
                Column::make(__('bt.balance'))
                    ->label(fn($row, Column $column) => $row->formatted_balance),
                BooleanColumn::make(__('bt.active'), 'active')
                    ->yesNo(),
                Column::make(trans('bt.created'), 'created_at')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('created_at', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->created_at)),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } elseif (!$statuses && $module_type == 'RecurringInvoice') { //RecurringInvoice column defs
            $frequencies = Frequency::lists();
            $default_columns = [
                Column::make(__('bt.number'), 'id')
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
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
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } elseif (!$statuses && $module_type == 'Payment') { //Payment column defs
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
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } elseif (!$statuses && $module_type == 'Expense') { //Expense column defs
            $default_columns = [
                Column::make(__('bt.date'), 'expense_date')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy('expense_date', $direction))
                    ->format(fn($value, $row, Column $column) => DateFormatter::format($row->expense_date)),
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable()
                    ->format(function ($value, $row, Column $column) {
                        $ret = $row->category->name;
                        if ($row->vendor->name)
                            $ret .= '<br><span class="text-muted">' . $row->vendor->name . '</span>';
                        return $ret;
                    })
                    ->html(),
                Column::make(trans('bt.description'), 'description')
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

                        return $ret;
                    })
                    ->html(),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row))
                ,
            ];
        } elseif ($module_type == 'TimeTrackingProject') { //Project column defs
            $default_columns = [
                Column::make(__('bt.project'), 'name')
                    ->sortable(),
                Column::make(__('bt.client'), 'client.name')
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
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } elseif (!$statuses && $module_type == 'Schedule') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->sortable(),
                Column::make(trans('bt.location'), 'location_str')
                    ->sortable(),
                Column::make(trans('bt.description'), 'description')
                    ->sortable(),
                Column::make(trans('bt.start_date'))
                    ->label(fn($row, Column $column) => $row->latestOccurrence->formatted_start_date),
                Column::make(__('bt.end_date'))
                    ->label(fn($row, Column $column) => $row->latestOccurrence->formatted_end_date),
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable(fn(Builder $query, string $direction) => $query->orderBy(Category::select('name')->whereColumn('category_id', 'id'), $direction)),
                Column::make('Action')
                    ->label(fn($row, Column $column) => view('utilities._actions')->withModel($row)),
            ];
        } else {
            return [];
        }

        return $default_columns;
    }
}
