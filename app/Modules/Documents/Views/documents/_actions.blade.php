<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href="{{ route('documents.edit', [$model->id]) }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href="{{ route('documents.pdf', [$model->id]) }}" target="_blank"
           id="btn-pdf-document"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @if (config('bt.mailConfigured'))
            <a class="dropdown-item email-document" href="javascript:void(0)" data-document-id="{{ $model->id }}"
               data-redirect-to="{{ request()->fullUrl() }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
{{--        <a class="dropdown-item" href="{{ route('clientCenter.public.document.show', [$model->url_key]) }}"--}}
{{--           target="_blank" id="btn-public-document"><i--}}
{{--                    class="fa fa-globe"></i> @lang('bt.public')</a>--}}
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('documents.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
