<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('recurringInvoices.edit', [$model->id]) }}"><i
                        class="fa fa-edit"></i> @lang('bt.edit')</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href ="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('recurringInvoices.delete', [$model->id]) }}');"><i
                        class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
