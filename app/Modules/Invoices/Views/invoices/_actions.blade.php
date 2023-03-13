
<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('invoices.edit', [$model->id]) }}"><i
                        class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href ="{{ route('invoices.pdf', [$model->id]) }}" target="_blank"
               id="btn-pdf-invoice"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @if (config('bt.mailConfigured'))
        <a class="dropdown-item email-invoice" href ="javascript:void(0)" data-invoice-id="{{ $model->id }}"
               data-redirect-to="{{ request()->fullUrl() }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
        <a class="dropdown-item" href ="{{ route('clientCenter.public.invoice.show', [$model->url_key]) }}"
               target="_blank" id="btn-public-invoice"><i
                        class="fa fa-globe"></i> @lang('bt.public')</a>

        @if ($model->isPayable or config('bt.allowPaymentsWithoutBalance'))
            <button class="dropdown-item"
                    type="button"
                    onclick="window.livewire.emit('showModal', 'modals.create-payment-modal', '{{  addslashes(get_class($model)) }}', {{ $model->id }}, true )"
            ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
            </button>
        @endif
        <div class="dropdown-divider"></div>

        @if($model->quote || $model->workorder)
            <a class="dropdown-item" href="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')','@lang('bt.trash_invoice_warning_assoc_msg')', '{{ route('invoices.delete', [$model->id]) }}');"><i
                        class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
        @else
            <a class="dropdown-item" href="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')', '@lang('bt.trash_invoice_warning_msg')', '{{ route('invoices.delete', [$model->id]) }}');"><i
                        class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
        @endif
    </div>
</div>
