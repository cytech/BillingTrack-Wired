<div class="btn-group">
    <button type="button" class="btn btn-secondary btn-sm"
            data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" href="{{ route('categories.edit', [$model->id]) }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        @if(! $model->in_use)
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.delete_record_warning')', '','{{ route('categories.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.delete')</a>
            @endif
    </div>
</div>
