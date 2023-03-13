<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm"
            data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item"
           href="{{ route('itemLookups.edit', [$model->id]) }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('itemLookups.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.delete')</a>
    </div>
</div>
