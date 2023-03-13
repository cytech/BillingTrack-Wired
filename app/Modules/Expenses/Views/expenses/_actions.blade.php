<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        @if ($model->is_billable and !$model->has_been_billed)
            <button type="button" class="btn btn-link dropdown-item btn-bill-expense"  data-expense-id="{{ $model->id }}"><i class="fa fa-dollar-sign"></i> @lang('bt.bill_this_expense')</button>
        @endif
        <a class="dropdown-item" href ="{{ route('expenses.edit', [$model->id]) }}"><i class="fa fa-edit"></i> @lang('bt.edit')</a>
            <div class="dropdown-divider"></div>
        <a class="dropdown-item" href ="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('expenses.delete', [$model->id]) }}');">
            <i class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
