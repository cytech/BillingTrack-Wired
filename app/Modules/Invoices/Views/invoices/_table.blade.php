<table class="table table-hover">
    <thead>
    <tr>
        <th>@lang('bt.status')</th>
        <th>@lang('bt.invoice')</th>
        <th>@lang('bt.date')</th>
        <th>@lang('bt.due')</th>
        <th>@lang('bt.summary')</th>
        <th style="text-align: right; padding-end: 25px;">@lang('bt.total')</th>
        <th style="text-align: right; padding-end: 25px;">@lang('bt.balance')</th>
        <th>@lang('bt.options')</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($invoices as $invoice)
        <tr>
            <td>
                <span class="badge badge-{{ $statuses[$invoice->invoice_status_id] }}">{{ trans('bt.' . $statuses[$invoice->invoice_status_id]) }}</span>
                @if ($invoice->viewed)
                    <span class="badge bg-success">@lang('bt.viewed')</span>
                @else
                    <span class="badge bg-secondary">@lang('bt.not_viewed')</span>
                @endif
            </td>
            <td><a href="{{ route('invoices.edit', [$invoice->id]) }}"
                   title="@lang('bt.edit')">{{ $invoice->number }}</a></td>
            <td>{{ $invoice->formatted_invoice_date }}</td>
            <td @if ($invoice->isOverdue) style="color: red; font-weight: bold;" @endif>{{ $invoice->formatted_due_at }}</td>
            <td>{{ mb_strimwidth($invoice->summary,0,100,'...') }}</td>
            <td style="text-align: right; padding-end: 25px;">{{ $invoice->amount->formatted_total }}</td>
            <td style="text-align: right; padding-end: 25px;">{{ $invoice->amount->formatted_balance }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
                        @lang('bt.options')
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" role="menu">
                        <a class="dropdown-item" href="{{ route('invoices.edit', [$invoice->id]) }}"><i
                                    class="fa fa-edit"></i> @lang('bt.edit')</a>
                        <a class="dropdown-item" href="{{ route('invoices.pdf', [$invoice->id]) }}" target="_blank"
                           id="btn-pdf-invoice"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
                        <a href="javascript:void(0)" class="email-invoice dropdown-item"
                           data-invoice-id="{{ $invoice->id }}"
                           data-redirect-to="{{ request()->fullUrl() }}"><i
                                    class="fa fa-envelope"></i> @lang('bt.email')</a>
                        <a class="dropdown-item"
                           href="{{ route('clientCenter.public.invoice.show', [$invoice->url_key]) }}"
                           target="_blank" id="btn-public-invoice"><i
                                    class="fa fa-globe"></i> @lang('bt.public')</a>
                        @if ($invoice->isPayable or config('bt.allowPaymentsWithoutBalance'))
                            <button class="dropdown-item"
                                    type="button"
{{--                                        params 3 thru ...>   mount($modulefullname = null, $module_id = null, $readonly = null){--}}
                                    onclick="window.livewire.emit('showModal', 'modals.create-payment-modal', '{{  addslashes(get_class($invoice)) }}', {{ $invoice->id }}, true )"
                            ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
                            </button>
                        @endif
                        <a class="dropdown-item" href="{{ route('invoices.delete', [$invoice->id]) }}"
                           onclick="return confirm('@lang('bt.trash_record_warning')');"><i
                                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
