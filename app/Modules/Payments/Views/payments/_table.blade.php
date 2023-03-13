<table class="table table-hover">
    <thead>
    <tr>
        <th>@lang('bt.payment_date')</th>
        <th>@lang('bt.invoice')</th>
        <th>@lang('bt.date')</th>
        <th>@lang('bt.summary')</th>
        <th>@lang('bt.amount')</th>
        <th>@lang('bt.payment_method')</th>
        <th>@lang('bt.note')</th>
        <th>@lang('bt.options')</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->formatted_paid_at }}</td>
            <td><a href="{{ route('invoices.edit', [$payment->invoice_id]) }}">{{ $payment->invoice->number }}</a></td>
            <td>{{ $payment->invoice->formatted_created_at }}</td>
            <td>{{ $payment->invoice->summary }}</td>
            <td>{{ $payment->formatted_amount }}</td>
            <td>@if ($payment->paymentMethod) {{ $payment->paymentMethod->name }} @endif</td>
            <td>{{ $payment->note }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
                        @lang('bt.options')
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" role="menu">
                        <a class="dropdown-item" href="{{ route('payments.edit', [$payment->id]) }}"><i
                                    class="fa fa-edit"></i> @lang('bt.edit')</a>
                        <a class="dropdown-item" href="{{ route('invoices.pdf', [$payment->invoice->id]) }}"
                           target="_blank" id="btn-pdf-invoice"><i class="fa fa-print"></i> @lang('bt.invoice')</a>
                        @if (config('bt.mailConfigured'))
                            <a href="javascript:void(0)" class="email-payment-receipt dropdown-item"
                               data-payment-id="{{ $payment->id }}" data-redirect-to="{{ request()->fullUrl() }}"><i
                                        class="fa fa-envelope"></i> @lang('bt.email_payment_receipt')</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('payments.delete', [$payment->id]) }}"
                           onclick="return confirm('@lang('bt.trash_record_warning')');"><i
                                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
