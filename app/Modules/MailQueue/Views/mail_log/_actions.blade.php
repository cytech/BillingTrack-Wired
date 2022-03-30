<div class="btn-group">
    <button type="button" class="btn btn-secondary btn-sm"
            data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('mailLog.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-red"></i> @lang('bt.delete')</a>
    </div>
</div>
