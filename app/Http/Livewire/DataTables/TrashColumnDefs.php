<?php

namespace BT\Http\Livewire\DataTables;

use BT\Support\Frequency;
use Rappasoft\LaravelLivewireTables\Views\Column;

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
                        ->sortable()
                        ->format(function ($value, $column, $row) {
                            return '<a href="/vendors/' . $row->vendor->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'formatted_due_at';
                    break;
                case 'Invoice':
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->sortable()
                        ->format(function ($value, $column, $row) {
                            return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.due');
                    $col_db_date_due = 'formatted_due_at';
                    break;
                default:
                    $col_client_vendor = Column::make(trans('bt.client'), 'client.name')
                        ->sortable()
                        ->format(function ($value, $column, $row) {
                            return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                        })
                        ->asHtml();
                    $col_title_date_due = trans('bt.expires');
                    $col_db_date_due = 'formatted_expires_at';
            }

            $default_columns = [
                Column::make(__('bt.status'), 'status_text')
                    ->sortable()
                    ->format(function ($value, $column, $row) use ($statuses) {
                        $ret = '<span class="badge badge-' . strtolower($value) . '">' . $statuses[$value] . '</span>';
                        if ($row->viewed)
                            $ret .= '<span class="badge bg-success">' . trans('bt.viewed') . '</span>';
                        else
                            $ret .= '<span class="badge bg-secondary">' . trans('bt.not_viewed') . '</span>';
                        return $ret;
                    })
                    ->
                    asHtml()
                ,
                Column::make(trans('bt.' . lcfirst($module_type)), 'number')
                    ->sortable()
                ,
                Column::make(trans('bt.date'), 'formatted_' . lcfirst($module_type) . '_date')
                    ->sortable()
                ,
                Column::make($col_title_date_due, $col_db_date_due)
                    ->sortable()
                ,
                $col_client_vendor
                ,
                Column::make(trans('bt.summary'), 'summary')
                    ->sortable()
                ,
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
                    ->hideIf($module_type <> 'Quote' && $module_type <> 'Workorder')
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif (!$statuses && $module_type == 'Payment') { //Payment column defs
            $default_columns = [
                Column::make(__('bt.payment_date'), 'formatted_paid_at')
                    ->sortable()
                ,
                Column::make(trans('bt.invoice'), 'invoice.number')
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        return '<a href="/invoices/' . $row->invoice_id . '/edit">' . $value . '</a>';
                    })
                    ->asHtml()
                ,
                Column::make(trans('bt.invoice_date'), 'invoice.formatted_invoice_date')
                    ->sortable()
                ,
                Column::make(__('bt.client'), 'client.name')
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml()
                ,
                Column::make(trans('bt.summary'), 'invoice.formatted_summary')
                    ->sortable()
                ,
                Column::make(trans('bt.amount'), 'formatted_amount')
                    ->sortable()
                ,
                Column::make(__('bt.payment_method'), 'paymentMethod.name')
                    ->sortable()
                ,
                Column::make(__('bt.note'), 'note')
                    ->sortable()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif (!$statuses && $module_type == 'RecurringInvoice') { //RecurringInvoice column defs
            $frequencies = Frequency::lists();
            $default_columns = [
                Column::make(__('bt.number'), 'id')
                    ->sortable()
                    ->addAttributes(['width' => '5%'])
                ,
                Column::make(__('bt.client'), 'client.name')
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml()
                ,
                Column::make(trans('bt.summary'), 'formatted_summary')
                    ->sortable()
                ,
                Column::make(trans('bt.next_date'), 'formatted_next_date')
                    ->sortable()
                ,
                Column::make(trans('bt.stop_date'), 'formatted_stop_date')
                    ->sortable()
                ,
                Column::make(__('bt.every'), 'recurring_frequency')
                    ->sortable()
                    ->format(function ($value, $column, $row) use ($frequencies) {
                        return $value . ' ' . $frequencies[$row->recurring_period];
                    })
                    ->
                    asHtml()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif (!$statuses && $module_type == 'Client') { //Client column defs
            $default_columns = [
                Column::make(__('bt.client_name'), 'name')
                    ->sortable()
                ,
                Column::make(trans('bt.email_address'), 'email')
                    ->sortable()
                ,
                Column::make(trans('bt.phone_number'), 'phone')
                    ->sortable()
                ,
                Column::make(__('bt.active'), 'active')
                    ->sortable()
                ,
                Column::make(trans('bt.created'), 'formatted_created_at')
                    ->sortable()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif (!$statuses && $module_type == 'Expense') { //Expense column defs
            $default_columns = [
                Column::make(__('bt.date'), 'formatted_expense_date')
                    ->sortable()
                ,
                Column::make(trans('bt.category'), 'category_name')
                    ->sortable()
                ,
                Column::make(trans('bt.description'), 'description')
                    ->sortable()
                ,
                Column::make(__('bt.amount'), 'formatted_amount')
                    ->sortable()
                ,
                Column::make(trans('bt.attachments'), 'expense.attachments')
                    ->sortable()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif ($module_type == 'TimeTrackingProject') { //Project column defs
            $default_columns = [
                Column::make(__('bt.project'), 'name')
                    ->sortable()
                ,
                Column::make(__('bt.client'), 'client.name')
                    ->sortable()
                    ->format(function ($value, $column, $row) {
                        return '<a href="/clients/' . $row->client->id . '">' . $value . '</a>';
                    })
                    ->asHtml()
                ,
                Column::make(trans('bt.status'), 'status_text')
                    ->sortable()
                ,
                Column::make(trans('bt.created'), 'formatted_created_at')
                    ->sortable()
                ,
                Column::make(trans('bt.due_date'), 'formatted_due_at')
                    ->sortable()
                ,
                Column::make(trans('bt.unbilled_hours'), 'unbilled_hours')
                    ->sortable()
                ,
                Column::make(__('bt.billed_hours'), 'billed_hours')
                    ->sortable()
                ,
                Column::make(__('bt.total_hours'), 'hours')
                    ->sortable()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } elseif (!$statuses && $module_type == 'Schedule') { //Scheduler column defs
            $default_columns = [
                Column::make(__('bt.title'), 'title')
                    ->sortable()
                ,
                Column::make(trans('bt.description'), 'description')
                    ->sortable()
                ,
                Column::make(trans('bt.start_date'), 'latestOccurrence.formatted_start_date')
                    ->sortable()
                ,
                Column::make(__('bt.end_date'), 'latestOccurrence.formatted_end_date')
                    ->sortable()
                ,
                Column::make(trans('bt.category'), 'category.name')
                    ->sortable()
                ,
                Column::make('Action')
                    ->format(function ($value, $column, $row) {
                        return view('utilities._actions')->withModel($row);
                    })
                ,
            ];
        } else {
            return [];
        }

        return $default_columns;
    }
}
