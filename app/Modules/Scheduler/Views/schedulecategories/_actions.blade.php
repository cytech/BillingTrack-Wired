<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('scheduler.categories.edit', [$model->id]) }}"><i class="fa fa-edit"></i> @lang('bt.edit')</a>
        @if($model->id > 10)
            <div class="dropdown-divider"></div>
        <a class="dropdown-item" href ="#"
               onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('scheduler.categories.delete', [$model->id]) }}');">
            <i class="fa fa-trash-alt text-danger"></i> @lang('bt.delete')</a>
        @endif
    </div>
</div>

