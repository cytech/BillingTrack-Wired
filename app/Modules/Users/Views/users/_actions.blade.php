<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('users.edit', [$model->id, $model->user_type]) }}"><i class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href ="{{ route('users.password.edit', [$model->id]) }}"><i class="fa fa-lock"></i> @lang('bt.reset_password')</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href ="#"
               onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('users.delete', [$model->id]) }}');">
            <i class="fa fa-trash-alt text-danger"></i> @lang('bt.delete')</a>
    </div>
</div>
