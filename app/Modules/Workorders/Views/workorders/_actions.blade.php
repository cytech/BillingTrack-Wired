<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href ="{{ route('workorders.edit', [$model->id]) }}"><i
                        class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href ="{{ route('workorders.pdf', [$model->id]) }}" target="_blank"
               id="btn-pdf-workorder"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        {{--<a class="dropdown-item email-workorder" href ="javascript:void(0)" data-workorder-id="{{ $model->id }}"--}}
               {{--data-redirect-to="{{ request()->fullUrl() }}"><i--}}
                        {{--class="fa fa-envelope"></i> @lang('bt.email')</a>--}}
        <a class="dropdown-item" href ="{{ route('clientCenter.public.workorder.show', [$model->url_key]) }}"
               target="_blank" id="btn-public-workorder"><i
                        class="fa fa-globe"></i> @lang('bt.public')</a>
        <div class="dropdown-divider"></div>
        @if($model->quote)
            <a class="dropdown-item" href="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')','@lang('bt.trash_workorder_warning_assoc_msg')', '{{ route('workorders.delete', [$model->id]) }}');"><i
                        class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
        @else
            <a class="dropdown-item" href="#"
               onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('workorders.delete', [$model->id]) }}');"><i
                        class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
        @endif
    </div>
</div>
