<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href="{{ route('documents.edit', [$model->id]) }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        @if($model->module_type == 'Purchaseorder' && !in_array($model->status_text, ['received', 'draft', 'canceled']))
            <a class="dropdown-item receive-purchaseorder" href="javascript:void(0)"
               data-purchaseorder-id="{{ $model->id }}"><i
                        class="fa fa-arrow-alt-circle-right"></i> @lang('bt.receive')</a>
        @endif
        @if($model->module_type != 'Recurringinvoice')
        <a class="dropdown-item" href="{{ route('documents.pdf', [$model->id]) }}" target="_blank"
           id="btn-pdf-document"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @endif
        @if (config('bt.mailConfigured'))
            <a class="dropdown-item email-document" href="javascript:void(0)" data-document-id="{{ $model->id }}"
               data-redirect-to="{{ request()->fullUrl() }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
        @if($model->module_type != 'Purchaseorder' && $model->module_type != 'Recurringinvoice')
            <a class="dropdown-item"
               href="{{ route('clientCenter.public.' . $model->lower_case_baseclass .'.show', [$model->url_key]) }}"
               target="_blank" id="btn-public-document"><i
                        class="fa fa-globe"></i> @lang('bt.public')</a>
            <div class="dropdown-divider"></div>
        @endif
        @if ($model->isPayable or config('bt.allowPaymentsWithoutBalance'))
            <button class="dropdown-item"
                    type="button"
                    onclick="window.livewire.emit('showModal', 'modals.create-payment-modal', '{{  addslashes(get_class($model)) }}', {{ $model->id }}, true )"
            ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
            </button>
        @endif
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('documents.delete', ['id' => $model->id, 'module_type' => $model->module_type]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
