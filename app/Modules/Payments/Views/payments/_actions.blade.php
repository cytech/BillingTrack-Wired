<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('payments.edit', [$model->id]) }}"><i class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href ="{{ route('invoices.pdf', [$model->invoice->id]) }}" target="_blank" id="btn-pdf-invoice"><i class="fa fa-print"></i> @lang('bt.invoice')</a>
        @if (config('bt.mailConfigured'))
            <a class="dropdown-item email-payment-receipt" href ="javascript:void(0)" data-payment-id="{{ $model->id }}" data-redirect-to="{{ request()->fullUrl() }}"><i class="fa fa-envelope"></i> @lang('bt.email_payment_receipt')</a>
        @endif
        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href ="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('payments.delete', [$model->id]) }}');">
            <i class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>

